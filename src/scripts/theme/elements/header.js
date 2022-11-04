
const body = document.querySelector('body');

const getHeaderHeight = () => {
  let height = 0;
  
  const header = document.getElementById('masthead');

  if (header) {
    height += header.offsetHeight;
  }

  // If the notifications bar exist add his Height.
  const headerNoticeBar = document.getElementById('header-notice-bar');
  if (headerNoticeBar) {
    height += headerNoticeBar.offsetHeight;
  }

  return height;
}
let headerHeight;

export default {
  init() {
   
  },
	finalize() {
    headerHeight = getHeaderHeight();
    body.style.setProperty('--goldencat--header--height', `${headerHeight}px`);
    window.addEventListener( 'resize', () => {
      if (headerHeight != getHeaderHeight()) {
        headerHeight = getHeaderHeight();
        body.style.setProperty('--goldencat--header--height', `${headerHeight}px`);
      }
    });
  }
}
