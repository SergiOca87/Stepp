<template>
  <div class="pagination" v-if="next > 0">
    <button @click.prevent="nextPage">Show {{ next }} more</button>
  </div>
</template>

<script>
export default {
  props: ["page", "per_page", "total"],
  computed: {
    next: function() {
      var next = this.total - this.per_page * this.page;
      return next < this.per_page ? next : this.per_page;
    }
  },
  methods: {
    nextPage: function() {
      eventHub.$emit("goToPage", this.page + 1);
    }
  }
};
</script>

