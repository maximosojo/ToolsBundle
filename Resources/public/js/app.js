
/*
 * This file is part of the Maximosojo Tools package.
 * 
 * (c) https://maximosojo.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

'use strict';

var app = angular.module('maximosojo_tools', ['ngTable'])

.directive('select2', function() {
    return {
        restrict: 'A',
        require: 'ngModel',
        scope: {
            ngModel: '='
        },
        link: function(scope, element, attr, ngModel) {
            element.on('change', function() {
                 var val = $(this).val();
                 scope.$apply(function(){
                    ngModel.$modelValue = val;
                    scope.ngModel = val;
                 });
            });
            ngModel.$render = function() {
                element.value = ngModel.$viewValue;
            }

        }
    }
})

.directive('ngLoading', function() {
    return {
        restrict: "A",
        scope: false,
        link: function (scope, element, attrs) {
            var loading = "<span class='ellipsis_animated-inner'>" +
                    "<span>.</span>" +
                    "<span>.</span>" +
                    "<span>.</span>" +
                    "</span>";
            var loadingLayer = angular.element("<div class='loading'><p class='blocktext'>" + loading + "</p></div>");
            element.addClass("ng-loading");
            scope.$watch(attrs.ngLoading, function (value) {
                if (value) {
                    element.append(loadingLayer);
                } else {
                    loadingLayer.remove();
                }
            });
        }
    };
})

.directive('apiDataUrl',function ($rootScope){  
  return {
    link: function(scope, element, attrs){
          $rootScope.apiDataUrl = attrs.apiDataUrl;
      }
  };
})

.controller('PaginatorController', function($scope, $rootScope, NgTableParams, $http, $timeout){
    var self = this;
    $scope.model = {};
    $scope.paginator = null;
    self.apiUrl = null;

    this.tableParams = new NgTableParams({
        page: 1, // show first page
        count: 10 // count per page
    }, {
        filterOptions: {
            filterDelay: 0
        },
        total: 0, // length of data
        getData: function (params) {
            var parameters = params.url();
            parameters.maxPerPage = parameters.count;
            var apiUrl = $scope.apiDataUrl;
            self.apiUrl = apiUrl;
            return $http.get(apiUrl, {params: parameters}).then(function (r) {
                var response = r.data;
                if(response && response.meta){
                    $scope.paginator = response;
                    params.total(response.meta.totalResults);
                    return response.data;
                }
            }).finally(function(){
                $rootScope.$broadcast('event_paginator_finally');
                self.tableParams.settings().$loading = false;
            });
        }
    });
    
    this.refresh = function () {
        self.tableParams.reload();
        self.getCurrentUrl();
        $rootScope.$broadcast('event_paginator_refresh');
    };
    
    $scope.$on("refresh_paginator",function(){
        self.refresh();
    })

    this.getCurrentUrl = function (baseUrl) {
        if (baseUrl == undefined) {
            baseUrl = self.apiUrl;
        }
        var s = "?";
        if (baseUrl.indexOf("?") != -1) {
            s = "&";
        }
        var url = baseUrl + s + jQuery.param(self.tableParams.url());
        return url;
    };
    
    this.getCurrentParams = function(returnObject){
        var params = self.tableParams.url();
        if(!returnObject){
            params = jQuery.param(params);
        }
        return params;

    };
    
    $scope.initWatch = function (modelName) {
        modelName = modelName.replace(".", "__");
        if ($scope.model[modelName]) {
            return;
        }
        $scope.model[modelName] = null;
        var lastChange = null;
        $scope.$watch("model." + modelName, function () {
            $timeout.cancel(lastChange);
            lastChange = $timeout(function () {
                var data = self.tableParams.filter();
                if (angular.isArray($scope.model[modelName])) {
                    if ($scope.model[modelName].length > 0) {
                        data[modelName] = angular.toJson($scope.model[modelName]);
                    } else {
                        data[modelName] = null;
                    }
                } else {
                    data[modelName] = $scope.model[modelName];
                }
                angular.forEach(data, function (v, k) {
                    if (null === v) {
                        delete data[k];
                    }
                });
                self.tableParams.filter(data);
            }, 1200);
        });
    };

    // $scope.$watch(function() {
    //         return self.checkboxes.checked;
    //     }, function(value) {
    //         angular.forEach(self.tableParams.data, function(item) {
    //             console.log(self.checkboxes)
    //             // self.checkboxes.items[item.id] = value;
    //         });
    //     }
    // );

    // $scope.names = function(column) {
    //     var def = $q.defer(),
    //         arr = [],
    //         names = [];
    //     angular.forEach(data, function(item){
    //         if (inArray(item.name, arr) === -1) {
    //             arr.push(item.name);
    //             names.push({
    //                 'id': item.name,
    //                 'title': item.name
    //             });
    //         }
    //     });
    //     def.resolve(names);
    //     return def;
    // };
    
    $scope.checkboxes = {
        checked: false,
        items: {}
    };

    // watch for check all checkbox
    $scope.$watch('checkboxes.checked', function(value) {
        angular.forEach(self.tableParams.data, function(item) {
            if (angular.isDefined(item.id)) {
                $scope.checkboxes.items[item.id] = value;
            }
        });
    });

    // watch for data checkboxes
    $scope.$watch('checkboxes.items', function(values) {
        if (!self.tableParams.data) {
            return;
        }
        var checked = 0, unchecked = 0,
            total = self.tableParams.data.length;
        angular.forEach(self.tableParams.data, function(item) {
            checked   +=  ($scope.checkboxes.items[item.id]) || 0;
            unchecked += (!$scope.checkboxes.items[item.id]) || 0;
        });
        if ((unchecked == 0) || (checked == 0)) {
            $scope.checkboxes.checked = (checked == total);
        }
        // grayed checkbox
        angular.element(document.getElementById("select_all")).prop("indeterminate", (checked != 0 && unchecked != 0));
    }, true);
})

.controller('ExporterController', function($scope, $rootScope, $http, $q){
    var self = this;
    this.files = [];
    this.name = null;
    $scope.loading = false;
    this.data = {};
    this.params = {};

    /**
     * Registro de parametros
     * @author  Máximo Sojo <maxsojo13@gmail.com>
     * @param   {Array}  $params
     */
    this.setParams = function(params) {
        this.params = params;
        self.getFiles();
    }

    /**
     * Llamado a generar el documento
     * @author  Máximo Sojo <maxsojo13@gmail.com>
     * @param   {Event}  $event
     * @return  {Boolean}
     */
    this.submit = function ($event) {
        $event.preventDefault();
        var form = angular.element('#form');
        var formData = form.serialize();
        var action = form.attr("action");
        var currentParams = "";
        
        if (typeof $scope.$parent != undefined && $scope.$parent.paginatorCtrl) {
            currentParams = "&" + $scope.$parent.paginatorCtrl.getCurrentParams();
        }

        var defered = $q.defer();
        var promise = defered.promise;
        $http({
            method: 'POST',
            url: action + currentParams,
            data: formData,
            headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' }  // set the headers so angular passing info as form data (not request payload)
        })
        .then(function (response) {
            var status = response.status;
            if (status == 400 || status == 500) {
                defered.reject(response);
            } else {
                self.getFiles();
                defered.resolve(response, status);
            }
        }, function (err, status, headers, config) {
            $rootScope.setErrors(err);
            defered.reject(err);
            progessService.stop();
        });
        return promise;
    };

    /**
     * Consulta los documentos
     * @author  Máximo Sojo <maxsojo13@gmail.com>
     * @return  {Json}
     */
    this.getFiles = function() {
        if (!this.params) {
            return;
        }

        var defered = $q.defer();
        var url = generateUrl("object_manager_documents_all",this.params)
        $http
        .get(url, {})
        .then(function (response) {
            if(!response){
                defered.reject(null);
                return;
            }
            var status = response.status;
            var data = response.data;
            if (status == 400 || status == 500) {
                defered.reject(response);
            } else {
                self.files = response.files;
                defered.resolve(data, status);
            }
        }, function (err) {
            defered.reject(err);
        })
        ;
    }

    /**
     * Descarga un documento
     * @author  Máximo Sojo <maxsojo13@gmail.com>
     */
    this.downloadFile = function (file) {
        this.params.filename = file.fileName;
        var url = generateUrl("object_manager_documents_download",this.params,true)
        window.location = url;
    };

    /**
     * Remueve un documento
     * @author  Máximo Sojo <maxsojo13@gmail.com>
     */
    this.deleteFile = function (file) {
        this.params.filename = file.fileName;
        var url = generateUrl("object_manager_documents_delete",this.params,true)
        window.location = url;
    };
})

.controller('TabsController', function(){
    var self = this;
    this.tab = null;
    this.currentTab = null;

    this.init = function(data){
        self.tab = data.tab;
        angular.forEach(self.tab.tabsContent,function(obj){
            if(obj.active){
                self.selectedTab(obj);
            }
        });
    };

    this.selectedTab = function(tab){
        if(tab == self.currentTab){
            return;
        }
        tab.loading = true;
        angular.forEach(self.tab.tabsContent,function(obj){
            obj.active = false;
        });
        tab.active = true;
        self.currentTab = tab;
    };

    this.getClsTab = function(tab){
        var cls = "";
        if(tab.active){
            cls = "active";
        }
        return cls;
    };

    this.loaded = function(tab){
        tab.loading = false;
    };
})

.controller('CollapseController', function($scope,$rootScope,$window){
    var self = this;
    self.url = null;
    self.class = "collapse";
    self.loading = false;
    self.urlRender = null;
    self.id = null;

    this.setId = function(id){
        if (!id) {
            return;
        }
        self.id = id;
        $window.localStorage.setItem("collapse_id",id);
    }

    this.collapse = function(id,url){
        self.setId(id);
        self.urlRender = url;
        // $window.localStorage.removeItem(key)
        this.show(false);
    }
    
    this.show = function(refresh){
        self.loading = true;
        if (self.class.indexOf("show") != -1 && !refresh) {
            self.class = "collapsing";
            self.class = "collapse";
        } else {
            if ($window.localStorage.getItem("collapse_id") == self.id) {
                if (!self.urlRender) {
                    return;
                }
                self.class = "collapsing";
                self.class = "collapse show";
                self.url = generateUrl(self.urlRender);
            }            
        }

        self.loading = false;
    }

    this.refresh = function(){
        this.show(true);
    }

    $scope.$on("refresh_collapse",function(){
        self.refresh();
    })
})

function generateUrl(route, parameters, cache) {
    if (parameters == null | parameters == undefined) {
        parameters = {};
    }

    if (!cache) {
        parameters['_dc'] = generateId();
    }
    
    return Routing.generate(route, parameters);
}

function s4() {
    return Math.floor((1 + Math.random()) * 0x10000)
            .toString(16)
            .substring(1);
}

function generateId() {
    var separator = "_";
    return s4() + s4() + separator + s4() + separator + s4() + separator +
            s4()
            + separator + s4() + s4() + s4();
}