Ext.require('Cetera.field.FileEditable');
Ext.require('Cetera.field.File');
Ext.require('Cetera.field.Ace');
Ext.require('Cetera.field.Image');
Ext.require('Ext.ux.ColorPicker');

Ext.define('Theme.simple.Config', {

    extend   :'Cetera.theme.Config',  

	bodyStyle:'padding:0; background: none',
	border   : false,
	layout: 'fit',
	
	colorSchemes: {
			default: {
					primary_color: '#F04124',
					secondary_color: '#008CBA',
					body_color: '#FFFFFF',
					text_color: '#555555',
					text_secondary_color: '#FFFFFF',
					heading_color: '#000000'
			},
			blue: {
					primary_color: '#00B0FF',
					secondary_color: '#006CBA',
					body_color: '#FFFFFF',
					text_color: '#000066',
					text_secondary_color: '#FFFFFF',
					heading_color: '#000000'
			},
			green: {
					primary_color: '#4caf50',
					secondary_color: '#4caf50',
					body_color: '#FFFFFF',
					text_color: '#555555',
					text_secondary_color: '#FFFFFF',
					heading_color: '#000000'
			},
			red: {
					primary_color: '#FF2800',
					secondary_color: '#FF8500',
					body_color: '#FFEFEF',
					text_color: '#770000',
					text_secondary_color: '#FFFFFF',
					heading_color: '#FF8500'
			},
			steel: {
					primary_color: '#666666',
					secondary_color: '#333333',
					body_color: '#FFFFFF',
					text_color: '#555555',
					text_secondary_color: '#FFFFFF',
					heading_color: '#000000'
			}			
	},
        	
    items: {
		xtype:'tabpanel',
        bodyStyle:'background: none;',
        border    : false,
        defaults:{
			bodyStyle:'background:none; padding:5px',
			border   : false,
			layout: 'anchor'
		},	
		
		items:[{
			title: 'Настройки шаблона',
			defaults: {
				labelWidth: 130,
				anchor: '100%'
			}, 	
			defaultType: 'textfield',			
			items: [
				{
					fieldLabel: 'Логотип',
					xtype: 'imagefield',
					height: 150,
					name: 'logo'
				},{
					fieldLabel: 'Favicon',
					xtype: 'fileselectfield',
					name: 'favicon'
				},{
					fieldLabel: 'Телефон',
					name: 'phone'
				},{
					fieldLabel: 'E-mail',
					name: 'email'
				},{
					fieldLabel: 'Адрес',
					name: 'address'
				},{
					fieldLabel: 'Страница VK',
					name: 'link_vk'
				},{
					fieldLabel: 'Страница Facebook',
					name: 'link_fb'
				},{
					fieldLabel: 'Подключить CSS',
					xtype: 'fileselect_editable',
					name: 'css',
					value: ''
				},{
					fieldLabel: 'Подключить JS',
					xtype: 'fileselect_editable',
					name: 'js',
					value: ''
				}
			]
		},{
			title: 'Цветовая схема',
			defaults: {
				labelWidth: 200,
				anchor: '100%'
			}, 		
			tbar: [
				'Готовые наборы:',
				{
					text: 'Стандартный',
					xtype: 'splitbutton',
					handler: function(b) {
						var f = b.findParentByType( 'form' );
						f.getForm().setValues(f.colorSchemes.default);
					},
					menu: {
						items: [
							{text: 'Голубая лагуна', handler: function(b){ var f = b.findParentByType( 'form' ); f.getForm().setValues(f.colorSchemes.blue);  }},
							{text: 'Изумруд', handler: function(b){ var f = b.findParentByType( 'form' ); f.getForm().setValues(f.colorSchemes.green);  }},
							{text: 'Хвост дракона', handler: function(b){ var f = b.findParentByType( 'form' ); f.getForm().setValues(f.colorSchemes.red);  }},
							{text: 'Сталь', handler: function(b){ var f = b.findParentByType( 'form' ); f.getForm().setValues(f.colorSchemes.steel);  }}
						]
					}
				}			
			],
			items: [
				{
					fieldLabel: 'Основной цвет',
					xtype: 'colorfield',
					name: 'primary_color'
				},{
					fieldLabel: 'Вспомогательный цвет',
					xtype: 'colorfield',
					name: 'secondary_color'
				},{
					fieldLabel: 'Цвет фона',
					xtype: 'colorfield',
					name: 'body_color'
				},{
					fieldLabel: 'Цвет текста',
					xtype: 'colorfield',
					name: 'text_color'
				},{
					fieldLabel: 'Цвет текста (дополнительный)',
					xtype: 'colorfield',
					name: 'text_secondary_color'
				},{
					fieldLabel: 'Цвет заголовков',
					xtype: 'colorfield',
					name: 'heading_color'
				}
			]
		},{
			title: 'Файлы',
			defaults: {
				labelWidth: 150,
				anchor: '100%'
			}, 		
			items: [
				{
					padding: '15 0 0 0',
					fieldLabel: 'Главный шаблон',
					xtype: 'fileselect_editable',
					name: 'layout_template',
					protectedValue: '/themes/simple/design/layout.twig'
				},{
					xtype: 'panel',
					bodyStyle:'background: none',
					border: false,
					html: '<p style="font-size:90%; margin: 0 0 0 160px">Изменяйте этот шаблон только в случае, если вы на 100% уверены в том, что делаете.</p>'
				},{
					xtype: 'fieldset',
					title: 'Блоки',
					collapsible: false,
					defaults: {
						labelWidth: 140,
						anchor: '100%'
					},            
					items: [
					
						{
							fieldLabel: 'Шапка',
							xtype: 'fileselect_editable',
							name: 'header_template',
							protectedValue: '/themes/simple/design/blocks/header.twig'
						},{
							fieldLabel: 'Подвал',
							xtype: 'fileselect_editable',
							name: 'footer_template',
							protectedValue: '/themes/simple/design/blocks/footer.twig'
						},{
							fieldLabel: 'Нижнее меню',
							xtype: 'fileselect_editable',
							name: 'bottommenu_template',
							protectedValue: '/themes/simple/design/blocks/bottommenu.twig'
						}				
					
					]
				},{
					xtype: 'fieldset',
					title: 'Шаблоны страниц',
					collapsible: false,
					defaults: {
						labelWidth: 140,
						anchor: '100%'
					},            
					items: [
					
						{
							fieldLabel: 'Главная страница',
							xtype: 'fileselect_editable',
							name: 'index_template',
							protectedValue: '/themes/simple/design/index.twig'
						},{
							fieldLabel: 'Рядовая страница',
							xtype: 'fileselect_editable',
							name: 'ordinary_template',
							protectedValue: '/themes/simple/design/ordinary.twig'
						},{
							fieldLabel: 'Страница 404',
							xtype: 'fileselect_editable',
							name: 'page404_template',
							protectedValue: '/themes/simple/design/404.twig'
						},{
							fieldLabel: 'Страница материала',
							xtype: 'fileselect_editable',
							name: 'material_template',
							protectedValue: '/themes/simple/design/material.twig'
						},{
							fieldLabel: 'Страница поиска',
							xtype: 'fileselect_editable',
							name: 'search_template',
							protectedValue: '/themes/simple/design/search.twig'
						}			
					
					]
				}
					
			] 			
		},{
			title: 'Коды и счетчики',
			defaults: {
				labelWidth: 120,
				anchor: '100%'
			},

			layout: {
				type: 'vbox',
				align: 'stretch'  // Child items are stretched to full width
			},			
			items: [
				{
					fieldLabel: 'Код в HEAD',
					xtype: 'acefield',
					flex: 1,
					name: 'code_head'
				},{
					xtype: 'panel',
					bodyStyle:'background: none',
					border: false,
					html: '<p style="font-size:90%; margin: 0 0 10px 130px">Код будет добавлен внутри тега &lt;HEAD&gt;</p>'
				},{
					fieldLabel: 'Код счетчиков',
					xtype: 'acefield',
					flex: 1,
					name: 'code_footer'
				}
					
			] 			
		}]
	}
});  