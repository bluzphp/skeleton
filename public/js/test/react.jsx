/**
 * React + REST API example
 *
 * @todo integrate http://react-bootstrap.github.io/
 */

/*global define,require*/
define(['react', 'react-dom', 'pager', 'jquery', 'bluz.notify'], function(React, ReactDOM, Pager, $, notify) {
    "use strict";

    /**
     * Table Row
     */
    var Row = React.createClass({
        handleEdit: function(e) {
            e.preventDefault();
            this.props.onEdit(this.props);
        },
        handleDelete: function(e) {
            e.preventDefault();
            if (confirm("Are you sure?")) {
                this.props.onDelete(this.props);
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
                    <button type="button" className="btn btn-default btn-xs" onClick={this.handleEdit}>
                        <span className="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </button>
                    &nbsp;
                    <button type="button" className="btn btn-danger btn-xs" onClick={this.handleDelete}>
                        <span className="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                </td>
            </tr>;
        }
    });

    /**
     * Table Body
     */
    var TBody = React.createClass({
        render: function() {
            var rowNodes = this.props.data.map(function(row, i) {
                return (
                    <Row key={row.id} id={row.id} name={row.name} email={row.email} status={row.status} onEdit={this.props.onEdit} onDelete={this.props.onDelete} />
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
     * Input Mixin
     */
    var InputMixin = {
        getDefaultProps: function () {
            return {
                name: 'field',
                title: 'Field',
                description: 'Some field'
            };
        },
        getInitialState: function () {
            return {
                value: this.props.value || '',
                error: null
            };
        },
        // hide notification
        handleClick: function (e) {
            this.setState({'error': null});
        },
        // call Form callback
        handleChange: function (e) {
            this.setState({value: e.currentTarget.value}, function () {
                this.props.onChange(this.state.value);
            });
        },
        value: function (value) {
            this.setState({value: value});
        },
        error: function (error) {
            this.setState({error: error});
        }
    };

    /**
     * Input Text Component
     */
    var TextInput = React.createClass({
        mixins: [InputMixin],
        render: function () {
            var error = this.state.error ? (
                <span className="error">{this.state.error}</span>
            ) : null;

            return (
                <div className={error ? 'form-group has-error' : 'form-group'}>
                    <label className="col-sm-2 control-label" for="inputName">
                        {this.props.title}
                    </label>
                    <div className="col-sm-10">
                        <input type="text" name={this.props.name} id="inputName"
                               className="form-control" placeholder={this.props.description}
                               value={this.state.value}
                               onClick={this.handleClick}
                               onChange={this.handleChange} />
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
        mixins: [InputMixin],
        render: function () {
            var error = this.state.error ? (
                <span className="error">{this.state.error}</span>
            ) : null;

            return (
            <div className={error ? 'form-group has-error' : 'form-group'}>
                <label className="col-sm-2 control-label">{this.props.title}</label>
                <div className="col-sm-10">
                    <select className="form-control"
                            value={this.state.value}
                            onChange={this.handleChange}>
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
        getInitialState: function() {
            return {id: '', name: '', email: '', status: 'active'};
        },
        handleNameChange: function(value) {
            this.setState({name: value});
        },
        handleEmailChange: function(value) {
            this.setState({email: value});
        },
        handleStatusChange: function(value) {
            this.setState({status: value});
        },
        handleSubmit: function(e) {
            e.preventDefault();
            var id = this.state.id.trim();

            var name = this.state.name.trim();
            if (!name) {
                this.refs.Name.error('Name is required');
            }

            var email = this.state.email.trim();
            if (!email) {
                this.refs.Email.error('Email is required');
            }

            var status = this.state.status.trim();

            if (!name || !email || !status) {
                return;
            }
            this.props.onSubmit({id: id, name: name, email: email, status: status});
            this.clear();
        },
        set: function(row) {
            this.setState(row, function() {
                this.refs.Name.value(this.state.name);
                this.refs.Email.value(this.state.email);
                this.refs.Status.value(this.state.status);
            });
        },
        clear: function() {
            this.setState({id: '', name: '', email: '', status: 'active'});
        },
        render: function() {
            return (
                <div id="modal-form" className="modal fade" tabindex="-1" role="dialog">
                    <div className="modal-dialog">
                        <div className="modal-content">
                            <form className="editForm form-horizontal" onSubmit={this.handleSubmit}>
                            <div className="modal-header">
                                <button type="button" className="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 className="modal-title">{this.state.id ? 'Edit' : 'Create'}</h4>
                            </div>
                            <div className="modal-body">
                                <input type="hidden" value={this.state.id}/>
                                <TextInput ref="Name" name="name" title="Name" description="User name" onChange={this.handleNameChange}/>
                                <TextInput ref="Email" name="email" title="Email" description="example@domain.com" onChange={this.handleEmailChange}/>
                                <SelectInput ref="Status" name="status" title="Status" description="" onChange={this.handleStatusChange}>
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

    /**
     * Container
     */
    var Container = React.createClass({
        getInitialState: function() {
            return {
                data: [],
                offset: 0,
                limit: 10,
                total: 0,
                current: 0,
                visiblePage: 3
            };
        },
        componentDidMount: function() {
            this.doRead(this.state.current);
        },
        showModal: function() {
            $('#modal-form').modal();
        },
        hideModal: function() {
            $('#modal-form').modal('hide');
        },
        handleCreate: function() {
            this.showModal();
            this.refs.Form.clear();
        },
        handleEditClick: function(row) {
            this.showModal();
            this.refs.Form.set(row);
        },
        handleDeleteClick: function(row) {
            this.doDelete(row);
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
            this.setState({ current : newPage });
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
            var offset = this.state.limit * newPage;

            $.ajax({
                url: this.props.url + '?offset='+offset+'&limit='+this.state.limit,
                dataType: 'json',
                cache: false,
                success: function(data, status, xhr) {
                    // e.g. Content-Range:items 0-10/96
                    var range = xhr.getResponseHeader("Content-Range");
                    var [, , , total] = range.split(/[ -\/]/g);

                    var pages = Math.ceil(total / this.state.limit);

                    // update data container and total value
                    this.setState({data: data, total: pages});
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
            var rows = this.state.data;
            var newRows = rows.concat([row]);

            this.setState({data: newRows});

            delete row.id;

            $.ajax({
                url: this.props.url,
                dataType: 'json',
                type: 'POST',
                data: row,
                success: function() {
                    notify.addSuccess('Record was created');
                }.bind(this),
                error: function(xhr, status, err) {
                    // notification
                    notify.addError('Error: ' + err.toString());
                    // rollback state
                    this.setState({data: rows});
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
            var rows = this.state.data;
            var newRows = this.state.data.slice();
            for (var i = 0; i < newRows.length; i++) {
                if (newRows[i].id == row.id) {
                    newRows[i] = row;
                    break;
                }
            }

            this.setState({data: newRows});

            $.ajax({
                url: this.props.url + '/' + row.id,
                dataType: 'json',
                type: 'PUT',
                data: row,
                success: function() {
                    notify.addSuccess('Record was update');
                }.bind(this),
                error: function(xhr, status, err) {
                    // notification
                    notify.addError('Error: ' + err.toString());
                    // rollback state
                    this.setState({data: rows});
                    // console
                    console.error(this.props.url, status, err.toString());
                }.bind(this)
            });
        },
        /**
         * HTTP DELETE method
         * @param row
         */
        doDelete(row) {
            var rows = this.state.data;

            var newRows = rows.filter(function(el) {
                return (el.id != row.id);
            });

            this.setState({data: newRows});

            $.ajax({
                url: this.props.url + '/' + row.id,
                dataType: 'json',
                type: 'DELETE',
                success: function() {
                    notify.addSuccess('Record was removed');
                }.bind(this),
                error: function(xhr, status, err) {
                    // notification
                    notify.addError('Error: ' + err.toString());
                    // rollback state
                    this.setState({data: rows});
                    // console
                    console.error(this.props.url, status, err.toString());
                }.bind(this)
            });
        },
        render: function() {
            return (
                <div className="Container">
                    <button className="btn btn-primary" onClick={this.handleCreate}>Create</button>
                    <hr/>
                    <Form ref="Form" onSubmit={this.handleSubmit} onEdit={this.handleEditForm} onClear={this.handleClearForm}/>
                    <table className="table grid">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <TBody data={this.state.data} onEdit={this.handleEditClick} onDelete={this.handleDeleteClick}/>
                    </table>
                    <Pager total={this.state.total}
                           current={this.state.current}
                           visiblePages={this.state.visiblePage}
                           onPageChanged={this.handlePageChanged}/>
                </div>
            );
        }
    });

    /**
     * Render
     */
    ReactDOM.render(
        <Container url="/test/rest"/>,
        document.getElementById('container')
    );
});