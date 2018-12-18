import $ from "jquery";

const getUrl = () => {
  if (window.location.href.includes('participants')) {
    return 'participants';
  }
  return 'staff';
};

const handleSubmit = e => {
  const search = $('.filter__search input').val();
  const sorting = $('.filter__sort select').find(":selected").val();
  const filter = $('.filter__role input[name=filterValue]:checked').val();
  const url = getUrl();
  const link = new URL(`http://127.0.0.1:8000/profile/${url}${sorting}`);
  const direction = link.searchParams.get('direction');
  const sort = link.searchParams.get('sort');
  const page = link.searchParams.get('page');

  e.preventDefault();

  if (url === 'participants') {
    window.location = `/profile/${url}/search?key=${search}&direction=${direction}&sort=${sort}&page=${page}`;
  } else {
    window.location = `/profile/${url}/search?key=${search}&direction=${direction}` +
      `&sort=${sort}&page=${page}&filterField=u.roles&filterValue=${filter}`;
  }
};

export default () => {
  const filterHolder =  $('.filter');

  filterHolder.submit(handleSubmit);
  $('.filter-toggle-button').on('click', () => filterHolder.slideToggle('fast'));
};