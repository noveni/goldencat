import { registerBlockVariation } from '@wordpress/blocks';

registerBlockVariation(
  'core/cover',
  {
    name: 'cover-slide',
    title: 'Cover Slide',
    // scope: ['block'],
    attributes: {
      url: 'https://starter.localhost/wp-content/uploads/2022/06/daniel-sessler-imxhx6qhvf0-unsplash.jpg',
      isDark: false,
      id: 102,
      dimRatio: 0
    },
    innerBlocks: [
      ['core/heading', { level: 1, textAlign: 'left', content: 'Titre' }, []],
      ['core/paragraph', { content: 'Lorem Ipsum', textAlign: 'left' }, []]
    ]
  }
)
