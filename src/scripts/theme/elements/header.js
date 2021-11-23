
const body = document.querySelector('body');

const getHeaderHeight = () => {
  const header = document.getElementById('masthead');
  if (header) {
    return header.offsetHeight;
  }
  return 0;
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
