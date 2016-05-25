/**
 * React + REST API example
 *
 * @todo integrate http://react-bootstrap.github.io/
 */

/*global define,require*/
define(
    ['react', 'react-dom', 'redux', 'react-redux', 'pager', 'jquery', 'underscore', 'bluz.notify'],
    function(React, ReactDOM, Redux, ReactRedux, Pager, $, _, notify) {
        "use strict";

        var Provider = ReactRedux.Provider;

        /**
         * Actions
         */
        const CREATE_FORM = 'CREATE_FORM';
        const EDIT_FORM = 'EDIT_FORM';
        const UPDATE_FORM = 'UPDATE_FORM';

        const CREATE_ROW = 'CREATE_ROW';
        const UPDATE_ROW = 'UPDATE_ROW';
        const DELETE_ROW = 'DELETE_ROW';

        const SET_DATA = 'SET_DATA';
        const SET_PAGE = 'SET_PAGE';

        /**
         * Clean form
         * @returns {{type: string, payload: {}}}
         */
        function createForm() {
            return {
                type: CREATE_FORM,
                payload: {}
            }
        }

        /**
         * Load edit form
         * @param id
         * @returns {{type: string, payload: {id: id}}}
         */
        function editForm(id) {
            return {
                type: EDIT_FORM,
                payload: {id: id}
            }
        }

        /**
         * Update form
         * @param row
         * @returns {{type: string, payload: {row: *}}}
         */
        function updateForm(row) {
            return {
                type: UPDATE_FORM,
                payload: {row: row}
            }
        }

        /**
         * Save new row
         * @param row
         * @returns {{type: string, payload: {row: *}}}
         */
        function createRow(row) {
            return {
                type: CREATE_ROW,
                payload: {row: row}
            }
        }

        /**
         * Update row in container
         * @param row
         * @returns {{type: string, payload: {row: *}}}
         */
        function updateRow(row) {
            return {
                type: UPDATE_ROW,
                payload: {row: row}
            }
        }

        /**
         * Delete row from data container
         * @param id
         * @returns {{type: string, payload: {id: *}}}
         */
        function deleteRow(id) {
            return {
                type: DELETE_ROW,
                payload: {id: id}
            }
        }

        /**
         * Load and display data
         * @param data
         * @param total
         * @returns {{type: string, payload: {data: *, total: *}}}
         */
        function setData(data, total) {
            return {
                type: SET_DATA,
                payload: {
                    data: data,
                    total: total
                }
            }
        }

        /**
         * Load page
         * @param page
         * @returns {{type: string, payload: {current: *}}}
         */
        function setPage(page) {
            return {
                type: SET_PAGE,
                payload: {current: page}
            }
        }

        /**
         * Reducers
         */
        const initialState = {
            // grid
            data: [],
            // form
            form: {
                id: '',
                name: '',
                email: '',
                status: 'active'
            },
            // navigation
            pager: {
                offset: 0,
                limit: 10,
                total: 0,
                current: 0,
                visiblePages: 5
            }
        };

        function mainApp(state, action) {
            if (typeof state === 'undefined') {
                return initialState;
            }

            let newState = _.extend({}, state);
            newState.form = _.extend({}, state.form);
            newState.pager = _.extend({}, state.pager);

            switch (action.type) {
                case CREATE_FORM:
                    newState.form = {id: '', name: '', email: '', status: 'active'};
                    break;
                case EDIT_FORM:
                    let elements = newState.data.filter(function(el) {
                        return (el.id == action.payload.id);
                    });
                    newState.form = elements[0];
                    break;
                case UPDATE_FORM:
                    _.extendOwn(newState.form, action.payload.row);
                    break;
                case CREATE_ROW:
                    break;
                case UPDATE_ROW:
                    for (var i = 0; i < newState.data.length; i++) {
                        if (newState.data[i].id == action.payload.row.id) {
                            newState.data[i] = action.payload.row;
                            break;
                        }
                    }
                    break;
                case DELETE_ROW:
                    newState.data = newState.data.filter(function(el) {
                        return (el.id != action.payload.id);
                    });
                    break;
                case SET_DATA:
                    newState.data = action.payload.data;
                    newState.pager.total = action.payload.total;
                    break;
                case SET_PAGE:
                    newState.pager.current = action.payload.current;
                    break;
                default:
                    return initialState;
            }
            return newState;
        }

        /**
         * Store
         */
        var store = Redux.createStore(mainApp);

        store.subscribe(function() {
            console.log('STORE: ', store.getState());
        });

        /**
         * Table Row
         */
        var TableRow = React.createClass({
            handleClickEdit: function(e) {
                e.preventDefault();
                this.props.onClickEdit(this.props.id);
            },
            handleClickDelete: function(e) {
                e.preventDefault();
                if (confirm("Are you sure want to delete this record?")) {
                    this.props.onClickDelete(this.props.id);
                }
            },
            render: function() {
                return <tr>
                    <td>
                        {this.props.name}
                    </td>
                    <td>
                        {this.props.email}
                    </td>
                    <td>
                        {this.props.status}
                    </td>
                    <td>
                        <button type="button" className="btn btn-default btn-xs" onClick={this.handleClickEdit}>
                            <span className="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                        </button>
                        &nbsp;
                        <button type="button" className="btn btn-danger btn-xs" onClick={this.handleClickDelete}>
                            <span className="glyphicon glyphicon-trash" aria-hidden="true"></span>
                        </button>
                    </td>
                </tr>;
            }
        });

        /**
         * Table Body
         */
        var TableBody = React.createClass({
            render: function() {
                var rowNodes = this.props.data.map(function(row, i) {
                    return (
                        <TableRow key={row.id} id={row.id} name={row.name} email={row.email} status={row.status} onClickEdit={this.props.onClickEdit} onClickDelete={this.props.onClickDelete} />
                    );
                }.bind(this));

                return (
                    <tbody>
                    {rowNodes}
                    </tbody>
                );
            }
        });

        /**
         * Table
         */
        var TableGrid = React.createClass({
            render: function() {
                console.log('TableGrid.render', this.props);
                return (
                    <table className="table grid">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <TableBody data={this.props.data} onClickEdit={this.props.onClickEdit} onClickDelete={this.props.onClickDelete} />
                    </table>
                );
            }
        });

        TableGrid = ReactRedux.connect(function(state) {
            return state;
        })(TableGrid);

        /**
         * Input Text Component
         */
        var TextInput = React.createClass({
            render: function () {
                var error = this.props.error ? (
                    <span className="error">{this.props.error}</span>
                ) : null;

                return (
                    <div className={error ? 'form-group has-error' : 'form-group'}>
                        <label className="col-sm-2 control-label" for={'id-'+this.props.name}>
                            {this.props.title}
                        </label>
                        <div className="col-sm-10">
                            <input type="text" name={this.props.name} id={'id-'+this.props.name}
                                   className="form-control" placeholder={this.props.description}
                                   value={this.props.value}
                                   onClick={this.props.onClick}
                                   onChange={this.props.onChange}/>
                            {error}
                        </div>
                    </div>
                );
            }
        });

        /**
         * Input Select Component
         */
        var SelectInput = React.createClass({
            render: function () {
                var error = this.props.error ? (
                    <span className="error">{this.props.error}</span>
                ) : null;

                return (
                    <div className={error ? 'form-group has-error' : 'form-group'}>
                        <label className="col-sm-2 control-label" for={'id-'+this.props.name}>{this.props.title}</label>
                        <div className="col-sm-10">
                            <select name={this.props.name} id={'id-'+this.props.name}
                                    className="form-control"
                                    value={this.props.value}
                                    onChange={this.props.onChange}>
                                {this.props.children}
                            </select>
                            {error}
                        </div>
                    </div>
                );
            }
        });

        /**
         * Create and Edit Form
         */
        var Form = React.createClass({
            handleChange: function(e) {
                e.preventDefault();

                let input = {};
                input[e.target.name] = e.target.value;

                this.props.onEdit(input);
            },
            handleClick: function(e) {
                e.preventDefault();
            },
            handleSubmit: function(e) {
                e.preventDefault();

                let form = e.target;
                let id = form.elements['id'].value;

                let name = form.elements['name'].value;
                if (!name) {
                    // TODO: validation
                    // this.refs.Name.error('Name is required');
                }

                let email = form.elements['email'].value;
                if (!email) {
                    // TODO: validation
                    // this.refs.Email.error('Email is required');
                }

                let status = form.elements['status'].value;

                if (!name || !email || !status) {
                    return;
                }
                this.props.onSubmit({id: id, name: name, email: email, status: status});
            },
            render: function() {
                return (
                    <div id="modal-form" className="modal fade" tabindex="-1" role="dialog">
                        <div className="modal-dialog">
                            <div className="modal-content">
                                <form className="editForm form-horizontal" onSubmit={this.handleSubmit}>
                                    <div className="modal-header">
                                        <button type="button" className="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 className="modal-title">{this.props.id ? 'Edit' : 'Create'}</h4>
                                    </div>
                                    <div className="modal-body">
                                        <input name="id" value={this.props.id} type="hidden"/>
                                        <TextInput name="name" value={this.props.name} error="" onChange={this.handleChange} onClick={this.handleClick} title="Name" description="User name"/>
                                        <TextInput name="email" value={this.props.email} error="" onChange={this.handleChange} onClick={this.handleClick} title="Email" description="example@domain.com"/>
                                        <SelectInput name="status" value={this.props.status} error="" onChange={this.handleChange} title="Status" description="">
                                            <option value="active">active</option>
                                            <option value="disable">disable</option>
                                            <option value="delete">delete</option>
                                        </SelectInput>
                                    </div>
                                    <div className="modal-footer">
                                        <button type="button" className="btn btn-default" data-dismiss="modal">Close</button>
                                        <button type="submit" className="btn btn-primary">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                );
            }
        });

        Form = ReactRedux.connect(function(state) {
            return state.form;
        })(Form);
        Pager = ReactRedux.connect(function(state) {
            return state.pager;
        })(Pager);

        /**
         * Container
         */
        var Container = React.createClass({
            componentDidMount: function() {
                this.doRead(this.props.pager.current);
            },
            showModal: function() {
                $('#modal-form').modal();
            },
            hideModal: function() {
                $('#modal-form').modal('hide');
            },
            handleCreateClick: function() {
                this.props.dispatch(createForm());
                this.showModal();
            },
            handleClickEdit: function(id) {
                this.props.dispatch(editForm(id));
                this.showModal();
            },
            handleClickDelete: function(id) {
                this.doDelete(id);
            },
            handleEdit: function(row) {
                this.props.dispatch(updateForm(row));
            },
            handleSubmit: function(row) {
                if (row.id == '') {
                    this.doCreate(row);
                } else {
                    this.doUpdate(row);
                }
                this.hideModal();
            },
            handlePageChanged: function ( newPage ) {
                this.props.dispatch(setPage(newPage));
                this.doRead(newPage);
            },
            /**
             * HTTP GET method
             * @param newPage
             */
            doRead: function(newPage) {
                /**
                 * Offset for page navigation
                 * @type {number}
                 */
                var offset = this.props.pager.limit * newPage;

                $.ajax({
                    url: this.props.url + '?offset='+offset+'&limit='+this.props.pager.limit,
                    dataType: 'json',
                    cache: false,
                    success: function(data, status, xhr) {
                        // e.g. Content-Range:items 0-10/96
                        var range = xhr.getResponseHeader("Content-Range");
                        var [, , , total] = range.split(/[ -\/]/g);

                        var pages = Math.ceil(total / store.getState().pager.limit);

                        // update data container and total value
                        this.props.dispatch(setData(data, pages));
                    }.bind(this),
                    error: function(xhr, status, err) {
                        // notification
                        notify.addError('Error: ' + err.toString());
                        // console
                        console.error(this.props.url, status, err.toString());
                    }.bind(this)
                });
            },
            /**
             * HTTP POST method
             * @param row
             */
            doCreate(row) {
                $.ajax({
                    url: this.props.url,
                    dataType: 'json',
                    type: 'POST',
                    data: row,
                    success: function() {
                        notify.addSuccess('Record was created');
                        this.props.dispatch(createRow(row));
                        this.doRead(this.props.pager.current);
                    }.bind(this),
                    error: function(xhr, status, err) {
                        // notification
                        notify.addError('Error: ' + err.toString());
                        // console
                        console.error(this.props.url, status, err.toString());
                    }.bind(this)
                });
            },
            /**
             * HTTP PUT method
             * @param row
             */
            doUpdate(row) {
                $.ajax({
                    url: this.props.url + '/' + row.id,
                    dataType: 'json',
                    type: 'PUT',
                    data: row,
                    success: function() {
                        notify.addSuccess('Record was update');
                        this.props.dispatch(updateRow(row));
                    }.bind(this),
                    error: function(xhr, status, err) {
                        // notification
                        notify.addError('Error: ' + err.toString());
                        // console
                        console.error(this.props.url, status, err.toString());
                    }.bind(this)
                });
            },
            /**
             * HTTP DELETE method
             * @param id
             */
            doDelete(id) {
                $.ajax({
                    url: this.props.url + '/' + id,
                    dataType: 'json',
                    type: 'DELETE',
                    success: function() {
                        notify.addSuccess('Record was removed');
                        this.props.dispatch(deleteRow(id));
                    }.bind(this),
                    error: function(xhr, status, err) {
                        // notification
                        notify.addError('Error: ' + err.toString());
                        // console
                        console.error(this.props.url, status, err.toString());
                    }.bind(this)
                });
            },
            render: function() {
                return (
                    <div className="Container">
                        <button className="btn btn-primary" onClick={this.handleCreateClick}>Create</button>
                        <hr/>
                        <Form onEdit={this.handleEdit} onSubmit={this.handleSubmit} />
                        <TableGrid onClickEdit={this.handleClickEdit} onClickDelete={this.handleClickDelete} />
                        <Pager onPageChanged={this.handlePageChanged}/>
                    </div>
                );
            }
        });

        Container = ReactRedux.connect(function(state) {
            return state;
        })(Container);

        /**
         * Render
         */
        ReactDOM.render(
            <Provider store={store}>
                <Container url="/test/rest"/>
            </Provider>,
            document.getElementById('container')
        );
    }
);