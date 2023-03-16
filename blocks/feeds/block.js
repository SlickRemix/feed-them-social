( function ( blocks, blockEditor, element ) {

    var RichText = blockEditor.RichText;
    var AlignmentToolbar = blockEditor.AlignmentToolbar;
    var BlockControls = blockEditor.BlockControls;
    var useBlockProps = blockEditor.useBlockProps;

    var el = element.createElement;

    blocks.registerBlockType( 'feed-them-social/feeds', {

        attributes: {
            feed: {
                type: 'string',
                default: 'facebook'
            },
            alignment: {
                type: 'string',
                default: 'none',
            },
        },

       
        edit: function ( props ) {

            var value = props.attributes.feed;
            var alignment = props.attributes.alignment;

            function onChangeContent( value, event ) {

                props.setAttributes(
                    {
                        feed: value
                    }
                )
            }

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
                    'No Feeds found.'
                );

            } else {

                let options = [
                    {
                        value: '',
                        label: 'Select a Feed'
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
                        label: 'Select a Feed',
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

        save: function ( props ) {
            return null;
        }

    } );
} )( window.wp.blocks, window.wp.blockEditor, window.wp.element );