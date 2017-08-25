import qs from 'qs';

var Search = (function($) {
  const { s } = qs.parse(window.location.search.substr(1));
  const search_regex = new RegExp(`(${s})`, 'i');

  return {
    highlightMatches: function() {
      $('.entry__summary').filter(function() {
        return search_regex.test($(this).find('p').text());
      }).find('p').html(function(index, html) {
        return html.replace(search_regex, '<span class="highlight-text">$1</span>');
      });
    }
  }

})(jQuery);

export default Search;
