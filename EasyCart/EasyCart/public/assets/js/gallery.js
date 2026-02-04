/**
 * Product detail gallery
 */

document.addEventListener('DOMContentLoaded', function() {
  const basePath = (window.EASYCART_BASE_PATH || '').replace(/\/$/, '');
  const currentPath = document.location.pathname;
  const normalizedPath = basePath && currentPath.startsWith(basePath)
    ? currentPath.slice(basePath.length)
    : currentPath;

  if (normalizedPath.match(/\/product(\/|$)/)) {
    const mainImg = document.querySelector('.product-main-image img');
    const thumbContainer = document.querySelector('.product-thumbnails');

    if (!mainImg || !thumbContainer) return;

    thumbContainer.innerHTML = '';

    const sources = [
      mainImg.src,
      mainImg.src,
      mainImg.src
    ];

    sources.forEach((src, index) => {
      const thumbBox = document.createElement('div');
      thumbBox.className = 'product-thumbnail';

      const thumb = document.createElement('img');
      thumb.src = src;

      if (index === 0) thumbBox.classList.add('active');

      thumbBox.appendChild(thumb);
      thumbContainer.appendChild(thumbBox);

      thumbBox.addEventListener('click', () => {
        mainImg.src = src;

        document
          .querySelectorAll('.product-thumbnail')
          .forEach(t => t.classList.remove('active'));

        thumbBox.classList.add('active');
      });
    });
  }
});
