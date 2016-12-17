@extends('adminlte::layouts.app')

@section('htmlheader_title')
  {{ trans('adminlte_lang::message.home') }}
@endsection
@section('contentheader_title')
  <h1>Simple Laravel Vue.Js Crud</h1>
@stop

@section('main-content')
  <div class="container-fluid">
    <div class="form-group row add">
      <div class="col-md-12">
        <button type="button" data-toggle="modal" data-target="#create-item" class="btn btn-primary">
          Create New Post
        </button>
      </div>
    </div>
    <div class="row">
      <div class="table-responsive">
        <table class="table table-borderless">
          <tr>
            <th>Title</th>
            <th>Article</th>
            <th>Type</th>
            <th>Category</th>
            <th>Actions</th>
          </tr>
          <tr v-for="item in data">
            <td>@{{ item.title }}</td>
            <td>@{{ item.article }}</td>
            <td>@{{ item.type }}</td>
            <td>@{{ item.category.name }}</td>
            <td>
              <button class="edit-modal btn btn-warning" @click.prevent="editItem(item)">
                <span class="glyphicon glyphicon-edit"></span> Edit
              </button>
              <button class="edit-modal btn btn-danger" @click.prevent="deleteItem(item)">
                <span class="glyphicon glyphicon-trash"></span> Delete
              </button>
            </td>
          </tr>
        </table>
      </div>
    </div>
    <nav>
      <ul class="pagination">
        <li v-if="meta.current > 1">
          <a href="#" aria-label="Previous" @click.prevent="changePage(meta.current - 1)">
            <span aria-hidden="true">«</span>
          </a>
        </li>
        <li v-for="page in pagesNumber" v-bind:class="[ page == isActived ? 'active' : '']">
          <a href="#" @click.prevent="changePage(page)">
            @{{ page }}
          </a>
        </li>
        <li v-if="meta.current < meta.last">
          <a href="#" aria-label="Next" @click.prevent="changePage(meta.current + 1)">
            <span aria-hidden="true">»</span>
          </a>
        </li>
      </ul>
    </nav>
  </div>
@stop

@push('modal')
  <!-- Create Item Modal -->
    <div class="modal fade" id="create-item" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Create New Post</h4>
          </div>
          <div class="modal-body">
            <form method="post" enctype="multipart/form-data" v-on:submit.prevent="createItem">
              <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" name="title" class="form-control" v-model="newItem.title" />
                <span v-if="formErrors['title']" class="error text-danger">
                  @{{ formErrors['title'] }}
                </span>
              </div>
              <div class="form-group">
                <label for="title">Article:</label>
                <textarea name="article" class="form-control" v-model="newItem.article">
                </textarea>
                <span v-if="formErrors['article']" class="error text-danger">
                  @{{ formErrors['article'] }}
                </span>
              </div>
              <div class="form-group">
                <label for="title">category id:</label>
                <input type="text" name="category_id" class="form-control" v-model="newItem.category_id" />
                <span v-if="formErrors['category_id']" class="error text-danger">
                  @{{ formErrors['category_id'] }}
                </span>
              </div>
              <div class="form-group">
                <label for="title">Image:</label>
                <input type="file" name="image" class="form-control" v-model="newItem.image" />
                <span v-if="formErrors['image']" class="error text-danger">
                  @{{ formErrors['image'] }}
                </span>
              </div>
              <div class="form-group">
                <label for="title">Writer id:</label>
                <input type="text" name="writer_id" class="form-control" v-model="newItem.writer_id" />
                <span v-if="formErrors['writer_id']" class="error text-danger">
                  @{{ formErrors['writer_id'] }}
                </span>
              </div>
              <div class="form-group">
                <label for="title">Type:</label>
                <input type="text" name="type" class="form-control" v-model="newItem.type" />
                <span v-if="formErrors['type']" class="error text-danger">
                  @{{ formErrors['type'] }}
                </span>
              </div>
              <div class="form-group">
                <label for="title">Url:</label>
                <input type="text" name="url" class="form-control" v-model="newItem.url" />
                <span v-if="formErrors['url']" class="error text-danger">
                  @{{ formErrorsnewItem['url'] }}
                </span>
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-success">Submit</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- Edit Item Modal -->
    <div class="modal fade" id="edit-item" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Edit Blog Post</h4>
          </div>
          <div class="modal-body">
            <form method="post" enctype="multipart/form-data" v-on:submit.prevent="updateItem(fillItem.id)">
              <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" name="title" class="form-control" v-model="fillItem.title" />
                <span v-if="formErrorsUpdate['title']" class="error text-danger">
                  @{{ formErrorsUpdate['title'] }}
                </span>
              </div>
              <div class="form-group">
                <label for="title">Description:</label>
                <textarea name="article" class="form-control" v-model="fillItem.article">
                </textarea>
                <span v-if="formErrorsUpdate['article']" class="error text-danger">
                  @{{ formErrorsUpdate['article'] }}
                </span>
              </div>
              <div class="form-group">
                <label for="title">category id:</label>
                <input type="text" name="category_id" class="form-control" v-model="fillItem.category_id" />
                <span v-if="formErrorsUpdate['category_id']" class="error text-danger">
                  @{{ formErrorsUpdate['writer_id'] }}
                </span>
              </div>
              <div class="form-group">
                <label for="title">Writer id:</label>
                <input type="text" name="writer_id" class="form-control" v-model="fillItem.writer_id" />
                <span v-if="formErrorsUpdate['writer_id']" class="error text-danger">
                  @{{ formErrorsUpdate['writer_id'] }}
                </span>
              </div>
              <div class="form-group">
                <label for="title">Type:</label>
                <input type="text" name="type" class="form-control" v-model="fillItem.type" />
                <span v-if="formErrorsUpdate['type']" class="error text-danger">
                  @{{ formErrorsUpdate['type'] }}
                </span>
              </div>
              <div class="form-group">
                <label for="title">Url:</label>
                <input type="text" name="url" class="form-control" v-model="fillItem.url" />
                <span v-if="formErrorsUpdate['url']" class="error text-danger">
                  @{{ formErrorsUpdate['url'] }}
                </span>
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-success">Submit</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>  
@endpush

@push('scripts')
  <script type="text/javascript" src="{{asset('/js/blog.js')}}"></script>
@endpush
