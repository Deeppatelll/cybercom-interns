/**
 * Products filters UI (and products count)
 */

document.addEventListener('DOMContentLoaded', function() {
  const basePath = (window.EASYCART_BASE_PATH || '').replace(/\/$/, '');
  const currentPath = document.location.pathname;
  const normalizedPath = basePath && currentPath.startsWith(basePath)
    ? currentPath.slice(basePath.length)
    : currentPath;

  if (normalizedPath.match(/\/products(\/|$)/)) {
    const grid = document.querySelector('.products-grid');
    const cards = grid ? grid.querySelectorAll('.product-card') : [];
    const count = cards.length;

    const title = document.querySelector('.page-title');
    if (title) {
      const span = document.createElement('span');
      span.textContent = ` (Showing ${count} Products)`;
      span.style.fontSize = '1rem';
      span.style.color = '#666';
      span.style.fontWeight = 'normal';
      span.style.marginLeft = '10px';
      title.appendChild(span);
    }
  }
});
