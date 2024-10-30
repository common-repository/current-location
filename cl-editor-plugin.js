// JavaScript Document
(function() {
        tinymce.PluginManager.requireLangPack('current_location');
        tinymce.create('tinymce.plugins.CurrentLocationPlugin', {
                init : function(ed, url) {
                        ed.addButton('current_location', {
                                title : 'Insert Current Location Tag',
                                image : url + '/icon.png',
																onclick : function() {
																	ed.focus();
																	ed.selection.setContent('[current-location]');
															 }
                        });
												ed.onNodeChange.add(function(ed, cm, n) {
                                cm.setActive('current_location', n.nodeName == 'IMG');
                        });
                },
                createControl : function(n, cm) {
                        return null;
                },
                getInfo : function() {
                        return {
                                longname : 'Current-Location Plugin',
                                author : 'Justin D. Givens',
                                authorurl : 'http://jdg.futbal.net/?from=tinymce',
                                infourl : 'http://jdg.futbal.net/index.php/current-location-plugin?from=tinymce',
                                version : "1.5.5"
                        };
                }
        });
        tinymce.PluginManager.add('current_location', tinymce.plugins.CurrentLocationPlugin);
})();