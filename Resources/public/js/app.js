
/*
 * This file is part of the Maxtoan Tools package.
 * 
 * (c) https://maximosojo.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

'use strict';

var app = angular.module('maxtoan_tools', ['ngTable'])

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
    $scope.model = {};
    $scope.paginator = null;
    this.apiUrl = null;
    var self = this;
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
                $scope.paginator = response;
                if(response && response.meta){
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
    
    this.postCurrentUrl = function (url) {
        var url = self.getCurrentUrl(url);
        clientService.post(url);
    };
    
    this.callCurrentUrl = function (url) {
        var url = self.getCurrentUrl(url);
        window.location = url;
    };

    this.clearFilters = function () {
        $timeout(function () {
          clearFilters();
        });
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