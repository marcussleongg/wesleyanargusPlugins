wp.blocks.registerBlockType('custom/co-authors', {
    title: 'Co-Authors',
    category: 'widgets',
    edit: function() {
        return wp.element.createElement('div', null, 'Co-Authors will be displayed here.');
    },
    save: function() {
        return null; // Rendered via PHP callback
    }
});