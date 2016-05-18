/**
 * React + REST API example
 *
 * @todo integrate http://react-bootstrap.github.io/
 */

/*global define,require*/
define(['react', 'react-dom', 'pager', 'jquery', 'bluz.notify'], function(React, ReactDOM, Pager, $, notify) {
    "use strict";

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

    var List = React.createClass({
        render: function() {
            var rowNodes = this.props.data.map(function(row, i) {
                return (
                    <Row key={i} id={row.id} name={row.name} email={row.email} status={row.status} onEdit={this.props.onEdit} onDelete={this.props.onDelete} />
                );
            }.bind(this));

            return (
                <tbody>
                {rowNodes}
                </tbody>
            );
        }
    });

    var Form = React.createClass({
        getInitialState: function() {
            return {id: '', name: '', email: '', status: 'active'};
        },
        handleNameChange: function(e) {
            this.setState({name: e.target.value});
        },
        handleEmailChange: function(e) {
            this.setState({email: e.target.value});
        },
        handleStatusChange: function(e) {
            this.setState({status: e.target.value});
        },
        handleSubmit: function(e) {
            e.preventDefault();
            var id = this.state.id.trim();
            var name = this.state.name.trim();
            var email = this.state.email.trim();
            var status = this.state.status.trim();
            if (!name || !email) {
                return;
            }
            this.props.onSubmit({id: id, name: name, email: email, status: status});
            this.clear();
        },
        set: function(row) {
            this.setState(row);
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
                                <h4 className="modal-title">Modal title</h4>
                            </div>
                            <div className="modal-body">
                                    <input type="hidden" value={this.state.id}/>
                                    <div className="form-group">
                                        <label className="col-sm-2 control-label" for="inputName">Name</label>
                                        <div className="col-sm-10">
                                        <input type="text" id="inputName" className="form-control" placeholder="User name"
                                               value={this.state.name}
                                               onChange={this.handleNameChange} />
                                        </div>
                                    </div>
                                    <div className="form-group">
                                        <label className="col-sm-2 control-label" for="inputEmail">Email</label>
                                        <div className="col-sm-10">
                                        <input type="email" id="inputEmail" className="form-control" placeholder="User email"
                                               value={this.state.email}
                                               onChange={this.handleEmailChange} />
                                        </div>
                                    </div>
                                    <div className="form-group">
                                        <label className="col-sm-2 control-label" for="inputStatus">Status</label>
                                        <div className="col-sm-10">
                                        <select className="form-control" id="inputStatus"
                                                value={this.state.status}
                                                onChange={this.handleStatusChange}>
                                            <option value="active">active</option>
                                            <option value="disable">disable</option>
                                            <option value="delete">delete</option>
                                        </select>
                                        </div>
                                    </div>
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
                if (newRows[i].id = row.id) {
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
                        <List data={this.state.data} onEdit={this.handleEditClick} onDelete={this.handleDeleteClick}/>
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