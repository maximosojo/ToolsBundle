
/*
 * This file is part of the Atechnologies package.
 * 
 * (c) www.atechnologies.com.ve
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

'use strict';

var app = angular.module('atechnologies', [])

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