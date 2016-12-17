Vue.http.headers.common['X-CSRF-TOKEN'] = $("#token").attr("value");
new Vue({
  el :'#manage-vue',
  data :{
    data: [],
    meta: {
      status: true,
      message: "List All Post",
      total: 7,
      offset: 10,
      current: 1,
      last: 1,
      next: null,
      prev: null,
      from: 1,
      to: 0,
      code: 200
    },
    offset: 4,
    formErrors:{},
    formErrorsUpdate:{},
    newItem : {'title':'','article':''},
    delItem : {'id':'','admin_id':'','writer_id':''},
    fillItem : {'title':'','article':'','category_id':'','id':''},
    host : 'http://api.enews.app/post'
  },
  computed: {
    isActived: function() {
      return this.meta.current;
    },
    pagesNumber: function() {
      if (!this.meta.to) {
        return [];
      }
      var from = this.meta.current - this.offset;
      if (from < 1) {
        from = 1;
      }
      var to = from + (this.offset * 2);
      if (to >= this.meta.last) {
        to = this.meta.last;
      }
      var pagesArray = [];
      while (from <= to) {
        pagesArray.push(from);
        from++;
      }
      return pagesArray;
    }
  },
  ready: function() {
    this.getVueItems(this.meta.current);
  },
  methods: {
    getVueItems: function(page) {
      this.$http.get(this.host + '?page='+page+'&sort=latest').then((response) => {
        this.$set('data', response.data.data);
        this.$set('meta', response.data.meta);
      });
    },
    createItem: function() {
      var input = this.newItem;
      this.$http.post(this.host,input).then((response) => {
        this.changePage(this.meta.current);
        this.newItem = {'title':'','article':''};
        $("#create-item").modal('hide');
        toastr.success('Post Created Successfully.', 'Success Alert', {timeOut: 5000});
      }, (response) => {
        this.formErrors = response.data.meta;
      });
    },
    deleteItem: function(item) {
      this.$http.delete(this.host + '/'+ item.id).then((response) => {
        this.changePage(this.meta.current);
        toastr.success('Post Deleted Successfully.', 'Success Alert', {timeOut: 5000});
      });
    },
    editItem: function(item) {
      this.fillItem.title = item.title;
      this.fillItem.id = item.id;
      this.fillItem.article = item.article;
      this.fillItem.category_id = item.category.id;
      $("#edit-item").modal('show');
    },
    updateItem: function(id) {
      var input = this.fillItem;
      this.$http.patch(this.host,input).then((response) => {
        this.changePage(this.meta.current);
        this.newItem = {'title':'','article':'','id':''};
        $("#edit-item").modal('hide');
        toastr.success('Item Updated Successfully.', 'Success Alert', {timeOut: 5000});
      }, (response) => {
        this.formErrors = response.data;
      });
    },
    changePage: function(page) {
      this.meta.current = page;
      this.getVueItems(page);
    }
  }
});