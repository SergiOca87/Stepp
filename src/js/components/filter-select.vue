<template>
  <div class="properties-filter-wrap">
    <select :name="name" @change="search()" v-if="!multiple">
      <option value>{{ empty }}</option>
      <option v-for="val in normalized_collection" :value="val.id">{{ val.name }}</option>
    </select>
    <div :class="{'multi-select': true, expanded: expanded}" :id="'ms-'+name" v-else>
      <div class="placeholder" @click="expanded = !expanded" v-html="placeholder"></div>
      <ul>
        <li v-for="val in normalized_collection">
          <input
            type="checkbox"
            :name="name+'[]'"
            :id="name+'-'+(typeof val.slug != 'undefined' ? val.slug : val.id)"
            :value="val.id"
            :data-label="val.name"
            @click="search()"
          >
          <label :for="name+'-'+(typeof val.slug != 'undefined' ? val.slug : val.id)">{{ val.name }}</label>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  props: ["name", "multiple", "collection", "empty"],
  data: function() {
    return {
      selected: [],
      expanded: false
    };
  },
  mounted: function() {
    var vm = this;
    if (this.multiple) {
      // listen for click outside show_location_filter
      document.addEventListener("mouseup", function(e) {
        if (vm.expanded) {
          var el = event.target;
          var inside = false;
          while (el) {
            if (el == document.getElementById("ms-" + vm.name)) {
              inside = true;
            }
            el = el.parentNode;
          }

          // close location filter if click outside
          if (inside === false) {
            vm.expanded = false;
          }
        }
      });
    }
  },
  computed: {
    placeholder: function() {
      if (this.multiple && this.selected.length) {
        return this.selected.join("");
      }
      return this.empty;
    },
    normalized_collection: function() {
      if (this.collection && this.collection.length) {
        var first_el = this.collection[0];
        if (first_el instanceof Object === false) {
          var normalized = [];
          this.collection.forEach(val => {
            normalized.push({
              id: val,
              name: val,
              slug: val
                .trim()
                .toLowerCase()
                .replace(" ", "-")
            });
          });
          return normalized;
        }
      }

      return this.collection;
    }
  },
  methods: {
    search: function() {
      var vm = this;
      if (this.multiple) {
        var p = document.getElementById("ms-" + this.name);
        // if no parent, stop

        // get checkboxes
        var checkboxes = p.querySelectorAll("input[type=checkbox]");
        // empty selected
        this.selected = [];
        // loop checkboxes to extract
        if (checkboxes.length) {
          checkboxes.forEach(el => {
            if (el.checked && el.nextElementSibling) {
              vm.selected.push(
                "<span>" + el.nextElementSibling.innerHTML + "</span>"
              );
            }
          });
        }
      }

      // propagate to parent
      this.$parent.search();
    }
  }
};
</script>

<style lang="scss">
.multi-select {
  position: relative;
  z-index: 100;
  user-select: none;
  ul {
    display: none;
    position: absolute;
    left: 0;
    top: 100%;
    width: 100%;
    background: #fff;
    padding: 0 0.5rem;
    margin: 0;
    list-style: none;
  }
  &.expanded {
    ul {
      display: block;
    }
  }
}
</style>

