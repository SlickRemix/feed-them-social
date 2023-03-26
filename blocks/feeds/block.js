( function ( blocks, blockEditor, element ) {

    const { __ } = wp.i18n;

    var AlignmentToolbar = blockEditor.AlignmentToolbar;
    var BlockControls = blockEditor.BlockControls;
    var useBlockProps = blockEditor.useBlockProps;

    var el = element.createElement;

    blocks.registerBlockType( 'feed-them-social/feeds', {

        attributes: {
            feed: {
                type: 'string',
            },
            alignment: {
                type: 'string',
                default: 'none',
            },
        },

        /**
         * Displays block content for the editor mode
         * 
         * @since 4.0.7
         * 
         * @param {object} props 
         * @returns Element-Object
         */
        edit: function ( props ) {

            var value = props.attributes.feed;
            var alignment = props.attributes.alignment;

            /**
             * Handles feed select changes.
             * 
             * @since 4.0.7
             * 
             * @param {String} value 
             * @param {Object} event 
             */
            function onChangeContent( value, event ) {

                props.setAttributes(
                    {
                        feed: value
                    }
                )
            }

            /**
             * Handles block alignment changes
             * 
             * @since 4.0.7
             * 
             * @param {String} newAlignment 
             */
            function onChangeAlignment( newAlignment ) {
                props.setAttributes( {
                    alignment:
                        newAlignment === undefined ? 'none' : newAlignment,
                } );
            }

            const userFeeds = feedThemSocialBlockFeeds;

            let feedElement = null;
            if ( 0 === userFeeds.length ) {

                feedElement = el(
                    'div',
                    {
                        style: { textAlign: alignment },
                    },
                    __( 'No Feeds found.', 'feed-them-social' )
                );

            } else {

                let options = [
                    {
                        value: '',
                        label: __( 'Select a Feed', 'feed-them-social' )
                    }
                ];
                
                userFeeds.forEach(
                    feed => {
                        options.push(
                            {
                                label: feed.Feed,
                                value: feed.ID
                            }
                        );
                    }
                );

                feedElement = el( 
                    wp.components.SelectControl,
                    {
                        label: __( 'Select a Feed', 'feed-them-social' ),
                        value: value,
                        options: options,
                        style: { textAlign: alignment },
                        onChange: onChangeContent
                    }
                )

            }

            return el(
                'div',
                useBlockProps(),
                el(
                    BlockControls,
                    { key: 'controls' },
                    el( AlignmentToolbar, {
                        value: alignment,
                        onChange: onChangeAlignment,
                    } )
                ),
                feedElement
            );
        },

        /**
         * Display the rendered block content
         * 
         * This is a dynamic block. The block content is rendered on the server side.
         * This means that this function must return null.
         * 
         * @param {object} props 
         * @returns null
         */
        save: function ( props ) {
            return null;
        }

    } );
} )( window.wp.blocks, window.wp.blockEditor, window.wp.element );