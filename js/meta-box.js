(function() {
    if (typeof wp !== 'undefined' && wp.editPost && wp.components && wp.element) {
        var PluginDocumentSettingPanel = wp.editPost.PluginDocumentSettingPanel;
        var RadioControl = wp.components.RadioControl;
        var SelectControl = wp.components.SelectControl;
        var PanelRow = wp.components.PanelRow;
        var Notice = wp.components.Notice;
        var useSelect = wp.data.useSelect;
        var useDispatch = wp.data.useDispatch;

        function KnittNetSettingsPanel() {
            var meta = useSelect(function(select) {
                return select('core/editor').getEditedPostAttribute('meta') || {};
            });

            var editPost = useDispatch('core/editor').editPost;

            // Determine effective visibility with backward compat
            var visibility = meta._knittnet_page_visibility || '';
            if (!visibility && meta._knittnet_hide_chatbot === '1') {
                visibility = 'hide';
            }

            var selectedBot = meta._knittnet_selected_bot || '';

            var elements = [];

            // Visibility radio control
            elements.push(
                wp.element.createElement(
                    PanelRow,
                    null,
                    wp.element.createElement(RadioControl, {
                        label: knittnetMetaBox.strings.visibilityLabel,
                        selected: visibility,
                        options: [
                            { label: knittnetMetaBox.strings.useGlobalSetting, value: '' },
                            { label: knittnetMetaBox.strings.showChatbot, value: 'show' },
                            { label: knittnetMetaBox.strings.hideChatbot, value: 'hide' }
                        ],
                        onChange: function(value) {
                            var newMeta = Object.assign({}, meta, {
                                _knittnet_page_visibility: value,
                                // Sync legacy field
                                _knittnet_hide_chatbot: value === 'hide' ? '1' : ''
                            });
                            editPost({ meta: newMeta });
                        }
                    })
                )
            );

            // Info notice about global setting
            elements.push(
                wp.element.createElement(
                    Notice,
                    {
                        status: 'info',
                        isDismissible: false,
                        className: 'knittnet-info-notice'
                    },
                    knittnetMetaBox.globalAutoshow
                        ? knittnetMetaBox.strings.globalAutoshowOn
                        : knittnetMetaBox.strings.globalAutoshowOff
                )
            );

            // Bot selection if multi-bot is available
            if (knittnetMetaBox.hasMultibot && knittnetMetaBox.availableBots) {
                var botOptions = [
                    { label: knittnetMetaBox.strings.useDefaultBot, value: '' }
                ];

                Object.keys(knittnetMetaBox.availableBots).forEach(function(botId) {
                    botOptions.push({
                        label: knittnetMetaBox.availableBots[botId],
                        value: botId
                    });
                });

                if (botOptions.length > 1) {
                    elements.push(
                        wp.element.createElement(
                            PanelRow,
                            null,
                            wp.element.createElement(SelectControl, {
                                label: knittnetMetaBox.strings.selectBot,
                                value: selectedBot,
                                options: botOptions,
                                onChange: function(value) {
                                    editPost({
                                        meta: Object.assign({}, meta, {
                                            _knittnet_selected_bot: value
                                        })
                                    });
                                }
                            })
                        )
                    );
                }
            }

            return wp.element.createElement(
                PluginDocumentSettingPanel,
                {
                    name: 'knittnet-settings',
                    title: knittnetMetaBox.strings.panelTitle,
                    className: 'knittnet-settings-panel'
                },
                elements
            );
        }

        wp.plugins.registerPlugin('knittnet-settings', {
            render: KnittNetSettingsPanel
        });
    }
})();
