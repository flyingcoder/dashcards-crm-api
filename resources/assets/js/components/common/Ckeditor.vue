<template>
  <div class="ckeditor">
    <textarea :id="id" :value="value"></textarea>
  </div>
</template>

<script>
export default {
  props: {
    value: {
      type: String,
      required: false
    },
    id: {
      type: String,
      required: false,
      default: 'editor'
    },
    height: {
      type: String,
      required: false,
      default: '90px',
    },
    config: {
      type: Object,
      required:false,
      default: () => ({
        toolbar: [
          ['Format'],
          ['Bold','Underline','Italic', 'Link', 'NumberedList','BulletedList','Image','BlockQoute','Undo','Redo']
        ],
        language: 'en'
      }) 
    },
  },
  mounted () {
    CKEDITOR.replace(this.id, this.config)
    CKEDITOR.instances[this.id].setData(this.value)
    CKEDITOR.instances[this.id].on('change', () => {
      let value = CKEDITOR.instances[this.id].getData()
      if (value !== this.value) {
        this.$emit('input', value)
      }
    })
		},
		destroyed () {
      if (CKEDITOR.instances[this.id]) {
        CKEDITOR.instances[this.id].destroy()
      }
		}
}
</script>
