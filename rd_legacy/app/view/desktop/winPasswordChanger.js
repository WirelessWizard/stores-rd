Ext.define('Rd.view.desktop.winPasswordChanger', {
    extend: 'Ext.window.Window',
    alias : 'widget.winPasswordChanger',
    title : i18n('sChange_password'),
    layout: 'fit',
    autoShow: false,
    width:    350,
    height:   250,
    iconCls: 'settings',

    initComponent: function() {
        var me = this;

        this.items = [
            {
                xtype: 'form',
                border:     false,
                layout:     'anchor',
                autoScroll: true,
                defaults: {
                    anchor: '100%'
                },
                fieldDefaults: {
                    msgTarget: 'under',
                    labelClsExtra: 'lblRd',
                    labelAlign: 'left',
                    labelSeparator: '',
                    margin: 15
                },
                defaultType: 'textfield',
                items: [
                    {  
                        name        : 'password',
                        itemId      : 'password',
                        allowBlank  : false,
                        inputType   : 'password',  
                        fieldLabel  : i18n('sPassword'),
                        labelClsExtra: 'lblRdReq'
                    },
                    {  
                        name        : 'confirm',
                        itemId      : 'confirm',
                        allowBlank  : false,
                        inputType   : 'password',  
                        fieldLabel  : i18n('sConfirm'),
                        labelClsExtra: 'lblRdReq',
                        vtype       : 'PasswordMatch'
                    }
                ],
                buttons: [
                    {
                        itemId: 'save',
                        text: i18n('sOK'),
                        scale: 'large',
                        iconCls: 'b-save',
                        formBind: true,
                        margin: Rd.config.buttonMargin
                    }
                ]
            }
        ];
        this.callParent(arguments);
    }
});
