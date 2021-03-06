Ext.define('CoovaChilli.view.pnlConnect', {
    extend: 'Ext.panel.Panel',
    alias : 'widget.pnlConnect',
    layout: 'fit',
    border: false,
    requires: ['Ext.toolbar.TextItem'],
    initComponent: function() {

        var me = this;
        var t_c_hidden = true;
        if(me.jsonData.settings.t_c_check == true){
            t_c_hidden = false;
        }

        var buttons;

        if(me.jsonData.settings.connect_check == true){
            if(me.jsonData.settings.connect_only == true){
                buttons = [
                    '->',
                    {
                        text        : 'Free Access',
                        action      : 'ok',
                        type        : 'submit',
                        itemId      : 'btnClickToConnect',
                        scale       : 'large',
                        glyph       : CoovaChilli.config.icnConnect
                    }
                ];

            }else{
                buttons = [
                    {
                        text        : 'Free Access',
                        action      : 'ok',
                        type        : 'submit',
                        itemId      : 'btnClickToConnect',
                        scale       : 'large',
                        glyph       : CoovaChilli.config.icnConnect
                    },
                    '|',
                    {
                        text        : 'Connect',
                        action      : 'ok',
                        type        : 'submit',
                        itemId      : 'btnConnect',
                        formBind    : true,
                        scale       : 'large',
                        glyph       : CoovaChilli.config.icnConnect
                    }  
                ];
            }
        }else{
            buttons = [
                '->',
                {
                    text        : 'Connect',
                    action      : 'ok',
                    type        : 'submit',
                    itemId      : 'btnConnect',
                    formBind    : true,
                    scale       : 'large',
                    glyph       : CoovaChilli.config.icnConnect
                }  
            ];
        }

		var remember_me = {
		    boxLabel  : 'Remember me',
		    name      : 'rememberMe',
		    inputValue: 'rememberMe',
		    labelAlign: 'right',
		    xtype     : 'checkbox',
		    itemId    : 'inpRememberMe',
			baseBodyCls: 'frmField'
		};


		//Form items depends on what is specified with user_login_check and voucher_login_check
		var frm_items_bottom = [
			
            {
                xtype       : 'displayfield',
                fieldLabel  : 'Terms & Conditions',
                labelStyle  : 'font-weight: bold; color: blue; font-size:120%;',
                fieldStyle  : 'color: #888282; font-style:italic; font-size:120%;',
                value       : "<a href='"+me.jsonData.settings.t_c_url+"' target='_blank'>"+me.jsonData.settings.t_c_url+"</a>",
                hidden      : t_c_hidden
            },
            {
                boxLabel  : 'Accept T&C',
                name      : 'chkTcCheck',
                inputValue: 'chkTcCheck',
                labelAlign: 'right',
                xtype     : 'checkbox',
                itemId    : 'chkTcCheck',
                padding   : '0 0 0 0',
                hidden    : t_c_hidden
            },
            {
                xtype       : 'displayfield',
                fieldLabel  : 'Error',
                labelStyle  : 'font-weight: bold; color: red; font-size:120%;',
                fieldStyle  : 'color: #888282; font-style:italic; font-size:120%;',
                itemId      : 'inpErrorDisplay',
                value       : '',
                hidden      : true
            } 
		];

		//The default
		if(
			(me.jsonData.settings.voucher_login_check == false)&&
			(me.jsonData.settings.user_login_check == true)&&
			(me.jsonData.settings.connect_only == false)
		){
			var frm_items_top = [
				 {
				    name        : "username",
				    fieldLabel  : 'Username',
				    itemId      : 'inpUsername',
				    allowBlank  : false,
				    blankText   : "Enter username",
					xtype		: 'textfield',
					baseBodyCls : 'frmField'
				},
				{
				    name        : "password",
				    fieldLabel  : 'Password',
				    itemId      : 'inpPassword',
				    inputType   : 'password',
				    allowBlank  : false,
				    blankText   : "Enter password",
					xtype		: 'textfield',
					baseBodyCls : 'frmField'
				},
				remember_me
			];
		}

		if(
			(me.jsonData.settings.voucher_login_check == true)&&
			(me.jsonData.settings.user_login_check == false)&&
			(me.jsonData.settings.connect_only == false)
		){
			var frm_items_top = [
				{
				    name        : "voucher",
					fieldLabel  : 'Voucher',
				    itemId      : 'inpVoucher',
				    allowBlank  : false,
				    blankText   : "Enter voucher",
					xtype		: 'textfield',
					baseBodyCls : 'frmField'
				},
				remember_me
			];
		}

		if(
			(me.jsonData.settings.voucher_login_check == true)&&
			(me.jsonData.settings.user_login_check == true)&&
			(me.jsonData.settings.connect_only == false)
		){
			var frm_items_top = [
			{
					xtype       : 'tabpanel',
					height		: 190,
					plain		: true,
					items		: [
						{
							title	: 'User',
							layout  : 'anchor',
            				height  : '100%',
							itemId  : 'tabUser',
							items	: [
							    {
							        name        : "username",
							        fieldLabel  : 'Username',
							        itemId      : 'inpUsername',
							        allowBlank  : false,
							        blankText   : "Enter username",
									xtype		: 'textfield',
									baseBodyCls : 'frmField'
							    },
							    {
							        name        : "password",
							        fieldLabel  : 'Password',
							        itemId      : 'inpPassword',
							        inputType   : 'password',
							        allowBlank  : false,
							        blankText   : "Enter password",
									xtype		: 'textfield',
									baseBodyCls : 'frmField'
							    }
							]
						},
						{
							title	: 'Voucher',
							layout  : 'anchor',
            				height  : '100%',
							itemId  : 'tabVoucher',
							items	: [
								{
							        name        : "voucher",
							        fieldLabel  : 'Voucher',
							        itemId      : 'inpVoucher',
							        allowBlank  : false,
							        blankText   : "Enter voucher",
									xtype		: 'textfield',
									baseBodyCls : 'frmField'
							    }
							]
						}
					]
				},
				remember_me
			];
		}	

		var frm_items  = Ext.Array.merge(frm_items_top, frm_items_bottom);

        me.items = [
            {
                xtype       : 'form',
                border      : false,
                layout      : 'anchor',
                height      : '100%',
                bodyPadding : '0 20 10 20',
                fieldDefaults: {
                    msgTarget   : 'under',
                    labelStyle  : 'font-weight: bold; color: #980820; font-size:120%;',
                    labelAlign  : 'top',
                    anchor      : '100%',
                    labelSeparator: ''
                },
                defaultType	: 'textfield',
               	items		: frm_items,
                buttons 	: buttons
            }
        ];
        me.callParent(arguments);
    }
});
