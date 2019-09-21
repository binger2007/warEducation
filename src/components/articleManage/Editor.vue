<template>
  <div class="edit_container">
    <quill-editor
      v-model="$store.state.content"
      ref="myQuillEditor"
      :options="editorOption"
      @blur="onEditorBlur($event)"
      @focus="onEditorFocus($event)"
      @change="onEditorChange($event)"
    ></quill-editor>
  </div>
</template>

<script>
import { quillEditor } from "vue-quill-editor"; //调用编辑器
import "quill/dist/quill.core.css";
import "quill/dist/quill.snow.css";
import "quill/dist/quill.bubble.css";
export default {
  name: "editor",
  components: {
    quillEditor
  },
  data() {
    return {
      editorOption: {
        modules: {
          toolbar: [
            ["bold", "italic"], // toggled buttons
            // [{ indent: "-1" }, { indent: "+1" }], // outdent/indent
            ["image"],
            [{ size: ["small", false, "large", "huge"] }], // custom dropdown

            [{ color: [] }], // dropdown with defaults from theme
            [{ align: [] }]
          ]
        }
      }
    };
  },
  methods: {
    onEditorReady(editor) {
      // 准备编辑器
    },
    onEditorBlur(obj) {}, // 失去焦点事件
    onEditorFocus() {}, // 获得焦点事件
    onEditorChange(obj) {
      this.$store.commit("getContent", obj.html);
    } // 内容改变事件
  },
  computed: {
    editor() {
      return this.$refs.myQuillEditor.quill;
    }
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.el-form-item__content * {
  line-height: 24px !important;
}
.ql-container {
  font-size: 16px;
}
.quill-editor {
  height: 300px;
  margin-bottom: 50px;
}
</style>
