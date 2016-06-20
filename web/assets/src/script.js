var React = require('react');
var ReactDOM = require('react-dom');
var $ = require('jquery');

/**
 * Filter component
 */
var Filter = React.createClass({
    changeFilter: function(e) {
        var state = this.state;
        state[e.target.name] = e.target.value;
        this.setState(state);
        this.props.onFilterChange(state);
    },
    createOption: function(item) {
        if (!item.id) {
            item = {id: item, name: item}
        }
        return (
            <option key={item.id} value={item.id}>{item.name}</option>
        );
    },
    getInitialState: function() {
        return {singer:'', genre:'', year:'', filters: 1};
    },
    render: function() {
        var filters = this.props.filters || {singers: [], genres: [], years: []}
        return (
            <div id="filter">
                <div className="panel panel-default">
                    <div className="panel-body">
                        <div>
                            <label>Исполнитель:</label><br/>
                            <select name="singer" onChange={this.changeFilter} value={this.state.singer}>
                                <option value="">---</option>
                                {filters.singers.map(this.createOption)}
                            </select>
                        </div>
                        <div>
                            <label>Жанр:</label><br/>
                            <select name="genre" onChange={this.changeFilter} value={this.state.genre}>
                                <option value="">---</option>
                                {filters.genres.map(this.createOption)}
                            </select>
                        </div>
                        <div>
                            <label>Год:</label><br/>
                            <select name="year" onChange={this.changeFilter} value={this.state.year}>
                                <option value="">---</option>
                                {filters.years.map(this.createOption)}
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
});

/**
 * Pagination component
 */
var Pagination = React.createClass({
    getInitialState: function() {
        return {limit:10, page:1, total:0};
    },
    changePage: function(e) {
        this.state.page = parseInt(e.target.textContent);
        var filter = {
            limit: this.state.limit,
            offset: (this.state.page-1) * this.state.limit
        }
        this.props.onPageChange(filter);
    },
    createPage: function(page) {
        if (page == this.state.page) {
            return (
                <li key={page} className="active"><a>{page}</a></li>
            );
        } else {
            return (
                <li key={page}><a onClick={this.changePage}>{page}</a></li>
            );
        }
    },
    render: function() {
        var state = this.state;
        state.total = this.props.total;
        var pages = Math.ceil(state.total / state.limit);
        var start = state.page - 5;
        var end = state.page + 5;
        if (start < 1)
            start = 1;
        if (end > pages)
            end = pages;

        var pagesList = [];

        for (var i = start; i <= end; i ++) {
            pagesList[pagesList.length] = i;
        }

        return (
            <ul className="pagination">
                {pagesList.map(this.createPage)}
            </ul>
        );
    }
});

/**
 * Song component
 */
var Song = React.createClass({
    render: function() {
        var song = this.props.song;
        return (
            <tr>
                <td>{song.singer.name}</td>
                <td>{song.title}</td>
                <td>{song.genre.name}</td>
                <td>{song.year}</td>
            </tr>
        );
    }
});

/**
 * Main playlist component
 */
var Playlist = React.createClass({
    loadSongs: function() {
        var filter = this.state.filter;
        $.ajax({
            url: this.props.url,
            dataType: 'json',
            data:filter,
            success: function(data) {
                this.setState(data);
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    },
    getInitialState: function() {
        return {rows:[], total: 0, filter:{filters:1, order: 'title asc'}, filters: {singers:[], genres:[], years:[]}};
    },
    componentDidMount: function() {
        this.loadSongs();
    },
    handleFilterChange: function(data) {
        var state = this.state;
        delete state['filter']['singer'];
        delete state['filter']['genre'];
        delete state['filter']['year'];
        state['filter'] = Object.assign(data, state['filter']);
        state['filter']['offset'] = 0;
        this.setState(state);
        this.loadSongs();
    },
    handlePageChange: function(filter) {
        var state = this.state;
        state['filter']['limit'] = filter.limit;
        state['filter']['offset'] = filter.offset;
        delete state['filter']['filters'];
        this.setState(state);
        this.loadSongs();
    },
    sorting: function(field) {
        var state = this.state;
        var lastOrder = state['filter']['order'].split(' ');
        var direction = 'asc';
        if (lastOrder[0] === field) {
            if (lastOrder[1] === 'asc') {
                direction = 'desc';
            } else {
                direction = 'asc';
            }
        }
        state['filter']['order'] = field + ' ' + direction;
        this.setState(state);
        this.loadSongs();
    },
    sortingIcon(field) {
        var order = this.state['filter']['order'].split(' ');

        if (order[0] === field) {
            if (order[1] === 'asc') {
                return (
                    <i className="glyphicon glyphicon-triangle-top"></i>
                );
            } else {
                return (
                    <i className=" glyphicon glyphicon-triangle-bottom"></i>
                );
            }
        }
    },
    render: function() {

        var songs = this.state.rows.map(function(song) {
            return (
                <Song key={song.id} song={song} />
            );
        });

        return (
            <div className="songList">
                <div className="col-lg-8">
                    <h3>Плэйлист</h3>
                    <table className="table">
                        <tbody>
                        <tr>
                            <th onClick={this.sorting.bind(this, 'singer')}>Исполнитель {this.sortingIcon('singer')}</th>
                            <th onClick={this.sorting.bind(this, 'title')}>Песня {this.sortingIcon('title')}</th>
                            <th onClick={this.sorting.bind(this, 'genre')}>Жанр {this.sortingIcon('genre')}</th>
                            <th onClick={this.sorting.bind(this, 'year')}>Год {this.sortingIcon('year')}</th>
                        </tr>
                        {songs}
                        </tbody>
                    </table>
                </div>
                <div className="col-lg-4">
                    <h3>Фильтр</h3>
                    <Filter filters={this.state.filters} onFilterChange={this.handleFilterChange}/>
                </div>
                <div className="col-lg-12">
                    <Pagination total={this.state.total} onPageChange={this.handlePageChange}/>
                </div>
            </div>
        );
    }
});

ReactDOM.render(
<Playlist url="/songs"/>,
    document.getElementById('content')
);