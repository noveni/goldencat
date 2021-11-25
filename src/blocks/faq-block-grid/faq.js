const toggleContent = (e) => {
  const {
    currentTarget
  } = e
  const parentBlock  = currentTarget.parentElement;
  parentBlock.classList.toggle('active');

}

let toggleBlockHeading;
export default {
  init() {
    toggleBlockHeading = document.querySelectorAll('.faq-item-head');
  },
  finalize() {
    // JavaScript to be fired after the init JS
    if (toggleBlockHeading.length) {
      toggleBlockHeading.forEach(e => e.addEventListener('click', toggleContent))
    }
  },
};
