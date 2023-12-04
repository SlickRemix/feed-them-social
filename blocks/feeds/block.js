( function ( blocks, blockEditor, element ) {

    const { __ } = wp.i18n;
    const AlignmentToolbar = blockEditor.AlignmentToolbar;
    const BlockControls = blockEditor.BlockControls;
    const useBlockProps = blockEditor.useBlockProps;

    const el = element.createElement;

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

        edit: function ( props ) {

            let value = props.attributes.feed;
            let alignment = props.attributes.alignment;

            function onChangeContent( value, event ) {
                props.setAttributes({ feed: value })
            }

            function onChangeAlignment( newAlignment ) {
                props.setAttributes({
                    alignment: newAlignment === undefined ? 'none' : newAlignment,
                });
            }

            const userFeeds = feedThemSocialBlockFeeds;

            let feedElement = null;
            if ( 0 === userFeeds.length ) {
                feedElement = el(
                    'div',
                    { style: { textAlign: alignment } },
                    __( 'No Feeds found.', 'feed-them-social' )
                );
            } else {
                let options = [
                    { value: '', label: __( 'Select a Feed', 'feed-them-social' ) }
                ];

                userFeeds.forEach(feed => {
                    options.push({ label: feed.Feed, value: feed.ID });
                });

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

        save: function ( props ) {
            return null;
        }

    } );
} )( window.wp.blocks, window.wp.blockEditor, window.wp.element );