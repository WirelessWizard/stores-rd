Ext.define('Rd.controller.cRealms', {
    extend: 'Ext.app.Controller',
    actionIndex: function(){

        var me = this;
        var desktop = this.application.getController('cDesktop');
        var win = desktop.getWindow('realmsWin');
        if(!win){
            win = desktop.createWindow({
                id: 'realmsWin',
               // title:i18n('sRealms_manager'),
                btnText:i18n('sRealms_manager'),
                width:800,
                height:400,
                iconCls: 'realms',
                glyph  : Rd.config.icnRealm,
                animCollapse:false,
                border:false,
                constrainHeader:true,
                layout: 'border',
                stateful: true,
                stateId: 'realmsWin',
                items: [
                    {
                        region: 'north',
                        xtype:  'pnlBanner',
                        heading:i18n('sRealms'),
                        image:  'resources/images/48x48/realms.png'
                    },
                    {
                        region  : 'center',
                        xtype   : 'panel',
                        layout  : 'fit',
                        border  : false,
                        items   : [{
                            xtype   : 'tabpanel',
                            layout  : 'fit',
                            margins : '0 0 0 0',
                            border  : true,
                            plain   : true,
                            items   : { 'title' : i18n('sHome'), 'xtype':'gridRealms','glyph': Rd.config.icnHome}}
                        ]
                    }
                ]
            });
        }
        desktop.restoreWindow(win);    
        return win;
    },
    views:  [
        'realms.gridRealms',                'realms.winRealmAddWizard', 'realms.winRealmAdd',   'realms.pnlRealm',  'components.pnlBanner',
        'components.winCsvColumnSelect',    'components.winNote',       'components.winNoteAdd','realms.pnlRealmDetail',
        'realms.pnlRealmLogo',              'components.pnlUsageGraph'
    ],
    stores: ['sRealms','sAccessProvidersTree'],
    models: ['mRealm','mAccessProviderTree', 'mUserStat'],
    selectedRecord: null,
    config: {
        urlAdd:             '/cake2/rd_cake/realms/add.json',
        urlEdit:            '/cake2/rd_cake/realms/edit.json',
        urlDelete:          '/cake2/rd_cake/realms/delete.json',
        urlApChildCheck:    '/cake2/rd_cake/access_providers/child_check.json',
        urlExportCsv:       '/cake2/rd_cake/realms/export_csv',
        urlNoteAdd:         '/cake2/rd_cake/realms/note_add.json',
        urlViewRealmDetail: '/cake2/rd_cake/realms/view.json',
        urlLogoBase:        '/cake2/rd_cake/webroot/img/realms/',
        urlUploadLogo:      '/cake2/rd_cake/realms/upload_logo/'
    },
    refs: [
         {  ref:    'gridRealms',           selector:   'gridRealms'},
         {  ref:    'gridAdvancedRealms',   selector:   'gridAdvancedRealms'},
         { ref:     'grid',                 selector:   'gridRealms'}
    ],
    init: function() {
        me = this;
        if (me.inited) {
            return;
        }
        me.inited = true;

        me.getStore('sRealms').addListener('load',me.onStoreRealmsLoaded, me);
        me.control({
            'gridRealms #reload': {
                click:      me.reload
            },
            'gridRealms #add': {
                click:      me.add
            },
            'gridRealms #delete': {
                click:      me.del
            },
            'gridRealms #edit': {
                click:      me.edit
            },
            'gridRealms #note'   : {
                click:      me.note
            },
            'gridRealms #csv'  : {
                click:      me.csvExport
            },
            'gridRealms'   : {
                itemclick:  me.gridClick
            },
            'winRealmAddWizard #btnTreeNext' : {
                click:  me.btnTreeNext
            },
            'winRealmAddWizard #btnRealmDetailPrev' : {
                click:  me.btnRealmDetailPrev
            },
            'winRealmAddWizard #save' : {
                click:  me.addSubmit
            },
            'pnlRealm pnlRealmDetail #save' : {
                click:  me.editSubmit
            },
            '#realmsWin':   {
                afterrender: me.onStoreRealmsLoaded //Prime it initially
            },
            '#winCsvColumnSelectRealms #save': {
                click:  me.csvExportSubmit
            },
            'gridNote[noteForGrid=realms] #reload' : {
                click:  me.noteReload
            },
            'gridNote[noteForGrid=realms] #add' : {
                click:  me.noteAdd
            },
            'gridNote[noteForGrid=realms] #delete' : {
                click:  me.noteDelete
            },
            'gridNote[noteForGrid=realms]' : {
                itemclick: me.gridNoteClick
            },
            'winNoteAdd[noteForGrid=realms] #btnTreeNext' : {
                click:  me.btnNoteTreeNext
            },
            'winNoteAdd[noteForGrid=realms] #btnNoteAddPrev'  : {   
                click: me.btnNoteAddPrev
            },
            'winNoteAdd[noteForGrid=realms] #btnNoteAddNext'  : {   
                click: me.btnNoteAddNext
            },
            'pnlRealm #tabDetail': {
                beforerender:   me.tabDetailActivate,
                activate:       me.tabDetailActivate
            },
            'pnlRealm #tabLogo': {
                activate:       me.tabLogoActivate
            },
            'pnlRealm #tabLogo #save': {
                click:       me.logoSave
            },
            'pnlRealm #tabLogo #cancel': {
                click:       me.logoCancel
            },
            'pnlRealm #pnlUsageGraphs #daily' : {
                activate:      me.loadGraph
            },
            'pnlRealm #pnlUsageGraphs #daily #reload' : {
                click:      me.reloadDailyGraph
            },
            'pnlRealm #pnlUsageGraphs #daily #day' : {
                change:      me.changeDailyGraph
            },
            'pnlRealm #pnlUsageGraphs #weekly' : {
                activate:      me.loadGraph
            },
            'pnlRealm #pnlUsageGraphs #weekly #reload' : {
                click:      me.reloadWeeklyGraph
            },
            'pnlRealm #pnlUsageGraphs #weekly #day' : {
                change:      me.changeWeeklyGraph
            },
            'pnlRealm #pnlUsageGraphs #monthly' : {
                activate:      me.loadGraph
            },
            'pnlRealm #pnlUsageGraphs #monthly #reload' : {
                click:      me.reloadMonthlyGraph
            },
            'pnlRealm #pnlUsageGraphs #monthly #day' : {
                change:      me.changeMonthlyGraph
            }  
        });;
    },
    reload: function(){
        var me =this;
        me.getGrid().getSelectionModel().deselectAll(true);
        me.getGrid().getStore().load();
    },
    gridClick:  function(grid, record, item, index, event){
        var me                  = this;
        me.selectedRecord = record;
        //Dynamically update the top toolbar
        tb = me.getGrid().down('toolbar[dock=top]');

        var edit = record.get('update');
        if(edit == true){
            if(tb.down('#edit') != null){
                tb.down('#edit').setDisabled(false);
            }
        }else{
            if(tb.down('#edit') != null){
                tb.down('#edit').setDisabled(true);
            }
        }

        var del = record.get('delete');
        if(del == true){
            if(tb.down('#delete') != null){
                tb.down('#delete').setDisabled(false);
            }
        }else{
            if(tb.down('#delete') != null){
                tb.down('#delete').setDisabled(true);
            }
        }
    },
    add: function(button){
        var me = this;
        //We need to do a check to determine if this user (be it admin or acess provider has the ability to add to children)
        //admin/root will always have, an AP must be checked if it is the parent to some sub-providers. If not we will simply show the add window
        //if it does have, we will show the add wizard.

        Ext.Ajax.request({
            url: me.urlApChildCheck,
            method: 'GET',
            success: function(response){
                var jsonData    = Ext.JSON.decode(response.responseText);
                if(jsonData.success){
                    if(jsonData.items.tree == true){
                        if(!me.application.runAction('cDesktop','AlreadyExist','winRealmAddWizardId')){
                            var w = Ext.widget('winRealmAddWizard',
                            {
                                id          :'winRealmAddWizardId'
                            });
                            me.application.runAction('cDesktop','Add',w);         
                        }
                    }else{
                        if(!me.application.runAction('cDesktop','AlreadyExist','winRealmAddWizardId')){
                            var w   = Ext.widget('winRealmAddWizard',
                            {
                                id          : 'winRealmAddWizardId',
                                startScreen : 'scrnData',
                                user_id     : '0',
                                owner       : i18n('sLogged_in_user'),
                                no_tree     : true
                            });
                            me.application.runAction('cDesktop','Add',w);       
                        }
                    }
                }   
            },
            scope: me
        });
    },
    btnTreeNext: function(button){
        var me = this;
        var tree = button.up('treepanel');
        //Get selection:
        var sr = tree.getSelectionModel().getLastSelected();
        if(sr){    
            var win = button.up('winRealmAddWizard');
            win.down('#owner').setValue(sr.get('username'));
            win.down('#user_id').setValue(sr.getId());
            win.getLayout().setActiveItem('scrnData');
        }else{
            Ext.ux.Toaster.msg(
                        i18n('sSelect_an_owner'),
                        i18n('sFirst_select_an_Access_Provider_who_will_be_the_owner'),
                        Ext.ux.Constants.clsWarn,
                        Ext.ux.Constants.msgWarn
            );
        }
    },
    btnRealmDetailPrev: function(button){
        var me = this;
        var win = button.up('winRealmAddWizard');
        win.getLayout().setActiveItem('scrnApTree');
    },
    addSubmit: function(button){
        var me      = this;
        var win     = button.up('window');
        var form    = win.down('form');
        form.submit({
            clientValidation: true,
            url: me.urlAdd,
            success: function(form, action) {
                win.close();
                me.getStore('sRealms').load();
                Ext.ux.Toaster.msg(
                    i18n('sNew_item_created'),
                    i18n('sItem_created_fine'),
                    Ext.ux.Constants.clsInfo,
                    Ext.ux.Constants.msgInfo
                );
            },
            //Focus on the first tab as this is the most likely cause of error 
            failure: function(form,action){
                var tp = win.down('tabpanel');
                tp.setActiveTab(0);
                Ext.ux.formFail(form,action)
            }
        });
    },
    del:   function(button){
        var me      = this;     
        //Find out if there was something selected
        if(me.getGrid().getSelectionModel().getCount() == 0){
             Ext.ux.Toaster.msg(
                        i18n('sSelect_an_item'),
                        i18n('sFirst_select_an_item_to_delete'),
                        Ext.ux.Constants.clsWarn,
                        Ext.ux.Constants.msgWarn
            );
        }else{
            Ext.MessageBox.confirm(i18n('sConfirm'), i18n('sAre_you_sure_you_want_to_do_that_qm'), function(val){
                if(val== 'yes'){
                   
                    var selected    = me.getGrid().getSelectionModel().getSelection();
                    var list        = [];
                    Ext.Array.forEach(selected,function(item){
                        var id = item.getId();
                        Ext.Array.push(list,{'id' : id});
                    });
                    Ext.Ajax.request({
                        url: me.urlDelete,
                        method: 'POST',          
                        jsonData: list,
                        success: function(batch,options){console.log('success');
                            Ext.ux.Toaster.msg(
                                i18n('sItem_deleted'),
                                i18n('sItem_deleted_fine'),
                                Ext.ux.Constants.clsInfo,
                                Ext.ux.Constants.msgInfo
                            );
                            me.reload(); //Reload from server
                        },                                    
                        failure: function(batch,options){
                            Ext.ux.Toaster.msg(
                                i18n('sProblems_deleting_item'),
                                batch.proxy.getReader().rawData.message.message,
                                Ext.ux.Constants.clsWarn,
                                Ext.ux.Constants.msgWarn
                            );
                            me.reload(); //Reload from server
                        }
                    });

                }
            });
        }
    },
    edit: function(button){
        var me = this;  
        //Find out if there was something selected
        if(me.getGrid().getSelectionModel().getCount() == 0){
             Ext.ux.Toaster.msg(
                        i18n('sSelect_an_item'),
                        i18n('sFirst_select_an_item'),
                        Ext.ux.Constants.clsWarn,
                        Ext.ux.Constants.msgWarn
            );
        }else{
            //Check if the node is not already open; else open the node:
            var tp      = me.getGrid().up('tabpanel');
            var sr      = me.getGrid().getSelectionModel().getLastSelected();
            var id      = sr.getId();
            var tab_id  = 'realmTab_'+id;
            var nt      = tp.down('#'+tab_id);
            if(nt){
                tp.setActiveTab(tab_id); //Set focus on  Tab
                return;
            }

            var tab_name = me.selectedRecord.get('name');
            //Tab not there - add one
            tp.add({ 
                title :     tab_name,
                itemId:     tab_id,
                closable:   true,
                iconCls:    'edit', 
                layout:     'fit', 
                items:      {'xtype' : 'pnlRealm',realm_id: id}
            });
            tp.setActiveTab(tab_id); //Set focus on Add Tab
/*            //Load the record:
            nt  = tp.down('#'+tab_id);
            var f   = nt.down('frmRealmDetail');
            f.loadRecord(sr);    //Load the record
            f.down('#owner').setValue(sr.get('owner')); 
*/  
        }
    },
    editSubmit: function(button){
        var me      = this;
        var form    = button.up('form');
        form.submit({
            clientValidation: true,
            url: me.urlEdit,
            success: function(form, action) {
                me.getStore('sRealms').load();
                Ext.ux.Toaster.msg(
                    i18n('sItem_updated'),
                    i18n('sItem_updated_fine'),
                    Ext.ux.Constants.clsInfo,
                    Ext.ux.Constants.msgInfo
                );
            },
            failure: Ext.ux.formFail
        });
    },
    onStoreRealmsLoaded: function() {
        var me = this;
        var count = me.getStore('sRealms').getTotalCount();
        me.getGridRealms().down('#count').update({count: count});
    },

     csvExport: function(button,format) {
        var me          = this;
        var columns     = me.getGrid().columns;
        var col_list    = [];
        Ext.Array.each(columns, function(item,index){
            if(item.dataIndex != ''){
                var chk = {boxLabel: item.text, name: item.dataIndex, checked: true};
                col_list[index] = chk;
            }
        }); 

        if(!me.application.runAction('cDesktop','AlreadyExist','winCsvColumnSelectRealms')){
            var w = Ext.widget('winCsvColumnSelect',{id:'winCsvColumnSelectRealms',columns: col_list});
            me.application.runAction('cDesktop','Add',w);         
        }
    },
    csvExportSubmit: function(button){

        var me      = this;
        var win     = button.up('window');
        var form    = win.down('form');

        var chkList = form.query('checkbox');
        var c_found = false;
        var columns = [];
        var c_count = 0;
        Ext.Array.each(chkList,function(item){
            if(item.getValue()){ //Only selected items
                c_found = true;
                columns[c_count] = {'name': item.getName()};
                c_count = c_count +1; //For next one
            }
        },me);

        if(!c_found){
            Ext.ux.Toaster.msg(
                        i18n('sSelect_one_or_more'),
                        i18n('sSelect_one_or_more_columns_please'),
                        Ext.ux.Constants.clsWarn,
                        Ext.ux.Constants.msgWarn
            );
        }else{     
            //next we need to find the filter values:
            var filters     = [];
            var f_count     = 0;
            var f_found     = false;
            var filter_json ='';
            me.getGrid().filters.filters.each(function(item) {
                if (item.active) {
                    f_found         = true;
                    var ser_item    = item.serialize();
                    ser_item.field  = item.dataIndex;
                    filters[f_count]= ser_item;
                    f_count         = f_count + 1;
                }
            });   
            var col_json        = "columns="+Ext.JSON.encode(columns);
            var extra_params    = Ext.Object.toQueryString(Ext.Ajax.extraParams);
            var append_url      = "?"+extra_params+'&'+col_json;
            if(f_found){
                filter_json = "filter="+Ext.JSON.encode(filters);
                append_url  = append_url+'&'+filter_json;
            }
            window.open(me.urlExportCsv+append_url);
            win.close();
        }
    },

    note: function(button,format) {
        var me      = this;    
        //Find out if there was something selected
        var sel_count = me.getGrid().getSelectionModel().getCount();
        if(sel_count == 0){
             Ext.ux.Toaster.msg(
                        i18n('sSelect_an_item'),
                        i18n('sFirst_select_an_item'),
                        Ext.ux.Constants.clsWarn,
                        Ext.ux.Constants.msgWarn
            );
        }else{
            if(sel_count > 1){
                Ext.ux.Toaster.msg(
                        i18n('sLimit_the_selection'),
                        i18n('sSelection_limited_to_one'),
                        Ext.ux.Constants.clsWarn,
                        Ext.ux.Constants.msgWarn
                );
            }else{

                //Determine the selected record:
                var sr = me.getGrid().getSelectionModel().getLastSelected();
                
                if(!me.application.runAction('cDesktop','AlreadyExist','winNoteRealms'+sr.getId())){
                    var w = Ext.widget('winNote',
                        {
                            id          : 'winNoteRealms'+sr.getId(),
                            noteForId   : sr.getId(),
                            noteForGrid : 'realms',
                            noteForName : sr.get('name')
                        });
                    me.application.runAction('cDesktop','Add',w);       
                }
            }    
        }
    },
    noteReload: function(button){
        var me      = this;
        var grid    = button.up('gridNote');
        grid.getStore().load();
    },
    noteAdd: function(button){
        var me      = this;
        var grid    = button.up('gridNote');

        //See how the wizard should be displayed:
        Ext.Ajax.request({
            url: me.urlApChildCheck,
            method: 'GET',
            success: function(response){
                var jsonData    = Ext.JSON.decode(response.responseText);
                if(jsonData.success){                      
                    if(jsonData.items.tree == true){
                        if(!me.application.runAction('cDesktop','AlreadyExist','winNoteRealmsAdd'+grid.noteForId)){
                            var w   = Ext.widget('winNoteAdd',
                            {
                                id          : 'winNoteRealmsAdd'+grid.noteForId,
                                noteForId   : grid.noteForId,
                                noteForGrid : grid.noteForGrid,
                                refreshGrid : grid
                            });
                            me.application.runAction('cDesktop','Add',w);       
                        }
                    }else{
                        if(!me.application.runAction('cDesktop','AlreadyExist','winNoteRealmsAdd'+grid.noteForId)){
                            var w   = Ext.widget('winNoteAdd',
                            {
                                id          : 'winNoteRealmsAdd'+grid.noteForId,
                                noteForId   : grid.noteForId,
                                noteForGrid : grid.noteForGrid,
                                refreshGrid : grid,
                                startScreen : 'scrnNote',
                                user_id     : '0',
                                owner       : i18n('sLogged_in_user'),
                                no_tree     : true
                            });
                            me.application.runAction('cDesktop','Add',w);       
                        }
                    }
                }   
            },
            scope: me
        });
    },
    gridNoteClick: function(item,record){
        var me = this;
        //Dynamically update the top toolbar
        grid    = item.up('gridNote');
        tb      = grid.down('toolbar[dock=top]');
        var del = record.get('delete');
        if(del == true){
            if(tb.down('#delete') != null){
                tb.down('#delete').setDisabled(false);
            }
        }else{
            if(tb.down('#delete') != null){
                tb.down('#delete').setDisabled(true);
            }
        }
    },
    btnNoteTreeNext: function(button){
        var me = this;
        var tree = button.up('treepanel');
        //Get selection:
        var sr = tree.getSelectionModel().getLastSelected();
        if(sr){    
            var win = button.up('winNoteAdd');
            win.down('#owner').setValue(sr.get('username'));
            win.down('#user_id').setValue(sr.getId());
            win.getLayout().setActiveItem('scrnNote');
        }else{
            Ext.ux.Toaster.msg(
                        i18n('sSelect_an_owner'),
                        i18n('sFirst_select_an_Access_Provider_who_will_be_the_owner'),
                        Ext.ux.Constants.clsWarn,
                        Ext.ux.Constants.msgWarn
            );
        }
    },
    btnNoteAddPrev: function(button){
        var me = this;
        var win = button.up('winNoteAdd');
        win.getLayout().setActiveItem('scrnApTree');
    },
    btnNoteAddNext: function(button){
        var me      = this;
        var win     = button.up('winNoteAdd');
        win.refreshGrid.getStore().load();
        var form    = win.down('form');
        form.submit({
            clientValidation: true,
            url: me.urlNoteAdd,
            params: {for_id : win.noteForId},
            success: function(form, action) {
                win.close();
                win.refreshGrid.getStore().load();
                me.reload();
                Ext.ux.Toaster.msg(
                    i18n('sNew_item_created'),
                    i18n('sItem_created_fine'),
                    Ext.ux.Constants.clsInfo,
                    Ext.ux.Constants.msgInfo
                );
            },
            failure: Ext.ux.formFail
        });
    },
    noteDelete: function(button){
        var me      = this;
        var grid    = button.up('gridNote');
        //Find out if there was something selected
        if(grid.getSelectionModel().getCount() == 0){
             Ext.ux.Toaster.msg(
                        i18n('sSelect_an_item'),
                        i18n('sFirst_select_an_item'),
                        Ext.ux.Constants.clsWarn,
                        Ext.ux.Constants.msgWarn
            );
        }else{
            Ext.MessageBox.confirm(i18n('sConfirm'), i18n('sAre_you_sure_you_want_to_do_that_qm'), function(val){
                if(val== 'yes'){
                    grid.getStore().remove(grid.getSelectionModel().getSelection());
                    grid.getStore().sync({
                        success: function(batch,options){
                            Ext.ux.Toaster.msg(
                                i18n('sItem_deleted'),
                                i18n('sItem_deleted_fine'),
                                Ext.ux.Constants.clsInfo,
                                Ext.ux.Constants.msgInfo
                            );
                            grid.getStore().load();   //Update the count
                            me.reload();   
                        },
                        failure: function(batch,options,c,d){
                            Ext.ux.Toaster.msg(
                                i18n('sProblems_deleting_item'),
                                batch.proxy.getReader().rawData.message.message,
                                Ext.ux.Constants.clsWarn,
                                Ext.ux.Constants.msgWarn
                            );
                            grid.getStore().load(); //Reload from server since the sync was not good
                        }
                    });
                }
            });
        }
    },
    tabDetailActivate : function(tab){
        var me      = this;
        var form    = tab.down('form');
        var realm_id= tab.up('pnlRealm').realm_id;
        form.load({url:me.urlViewRealmDetail, method:'GET',params:{realm_id:realm_id}});
    },
    tabLogoActivate: function(tab){
        var me      = this;
        var pnl_n   = tab.up('pnlNas');
        var realm_id= tab.up('pnlRealm').realm_id;
        var p_img   = tab.down('#pnlImg');
        Ext.Ajax.request({
            url: me.urlViewRealmDetail,
            method: 'GET',
            params: {realm_id : realm_id },
            success: function(response){
                var jsonData    = Ext.JSON.decode(response.responseText);
                if(jsonData.success){
                    var img_url = me.urlLogoBase+jsonData.data.icon_file_name;
                    p_img.update({image:img_url});
                }   
            },
            scope: me
        });
    },
    logoSave: function(button){
        var me      = this;
        var form    = button.up('form');
        var pnl_r   = form.up('pnlRealm');
        var p_form  = form.up('panel');
        var p_img   = p_form.down('#pnlImg');
        form.submit({
            clientValidation: true,
            waitMsg: 'Uploading your photo...',
            url: me.urlUploadLogo,
            params: {'id' : pnl_r.realm_id },
            success: function(form, action) {              
                if(action.result.success){ 
                    var new_img = action.result.icon_file_name;    
                    var img_url = me.urlLogoBase+new_img;
                    p_img.update({image:img_url});
                } 
                Ext.ux.Toaster.msg(
                    i18n('sItem_updated'),
                    i18n('sItem_updated_fine'),
                    Ext.ux.Constants.clsInfo,
                    Ext.ux.Constants.msgInfo
                );
            },
            failure: Ext.ux.formFail
        });
    },
    logoCancel: function(button){
        var me      = this;
        var form    = button.up('form');
        form.getForm().reset();
    },
    loadGraph: function(tab){
        var me  = this;
        tab.down("chart").setLoading(true);
        //Get the value of the Day:
        var day = tab.down('#day');
        tab.down("chart").getStore().getProxy().setExtraParam('day',day.getValue());
        me.reloadChart(tab);
    },
    reloadDailyGraph: function(btn){
        var me  = this;
        tab     = btn.up("#daily");
        me.reloadChart(tab);
    },
    changeDailyGraph: function(d,new_val, old_val){
        var me      = this;
        var tab     = d.up("#daily");
        tab.down("chart").getStore().getProxy().setExtraParam('day',new_val);
        me.reloadChart(tab);
    },
    reloadWeeklyGraph: function(btn){
        var me  = this;
        tab     = btn.up("#weekly");
        me.reloadChart(tab);
    },
    changeWeeklyGraph: function(d,new_val, old_val){
        var me      = this;
        var tab     = d.up("#weekly");
        tab.down("chart").getStore().getProxy().setExtraParam('day',new_val);
        me.reloadChart(tab);
    },
    reloadMonthlyGraph: function(btn){
        var me  = this;
        tab     = btn.up("#monthly");
        me.reloadChart(tab);
    },
    changeMonthlyGraph: function(d,new_val, old_val){
        var me      = this;
        var tab     = d.up("#monthly");
        tab.down("chart").getStore().getProxy().setExtraParam('day',new_val);
        me.reloadChart(tab);
    },
    reloadChart: function(tab){
        var me      = this;
        var chart   = tab.down("chart");
        chart.setLoading(true); //Mask it
        chart.getStore().load({
            scope: me,
            callback: function(records, operation, success) {
                chart.setLoading(false);
                if(success){
                    Ext.ux.Toaster.msg(
                            "Graph fetched",
                            "Graph detail fetched OK",
                            Ext.ux.Constants.clsInfo,
                            Ext.ux.Constants.msgInfo
                        );
                    //-- Show totals
                    var rawData     = chart.getStore().getProxy().getReader().rawData;
                    var totalIn     = Ext.ux.bytesToHuman(rawData.totalIn);
                    var totalOut    = Ext.ux.bytesToHuman(rawData.totalOut);
                    var totalInOut  = Ext.ux.bytesToHuman(rawData.totalInOut);
                    tab.down('#totals').update({'in': totalIn, 'out': totalOut, 'total': totalInOut });

                }else{
                    Ext.ux.Toaster.msg(
                            "Problem fetching graph",
                            "Problem fetching graph detail",
                            Ext.ux.Constants.clsWarn,
                            Ext.ux.Constants.msgWarn
                        );
                } 
            }
        });   
    }
});
