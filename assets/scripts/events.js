// quick touchscreen detection, from Modernizr
// caution: http://www.stucox.com/blog/you-cant-detect-a-touchscreen/
export function hasTouch() {
  return (('ontouchstart' in window) || window.DocumentTouch && document instanceof DocumentTouch);
}
