<template>
    <v-stage :config="stage">
    <v-layer>
      <v-circle :config="configCircle"></v-circle>
      <v-circle :config="configCircle"></v-circle>
    </v-layer>
  </v-stage>
</template>

<script>
export default {
  data() {
    return {
      sceneWidth : 900,
      sceneHeight : 450,
      
      stage: {
        container: 'container',
        width: this.sceneWidth,
        height: this.sceneHeight,
      },
      configCircle: {
        x: 0,
        y: 0,
        radius: 70,
        fill: "red",
        stroke: "black",
        strokeWidth: 4,
        draggable:true
      },
     
    };
  },
  created(){    
      this.fitStageIntoParentContainer();
      // adapt the stage on any window resize
      window.addEventListener('resize', this.fitStageIntoParentContainer);
  },
  methods: {
        fitStageIntoParentContainer() {
        this.container = document.querySelector('#stage-parent');

        // now we need to fit stage into parent container
        var containerWidth = this.container.offsetWidth;

        // but we also make the full scene visible
        // so we need to scale all objects on canvas
        var scale = containerWidth / this.sceneWidth;

        this.stage.width = this.sceneWidth * scale;
        this.stage.height = this.sceneHeight * scale;
        this.stage.scale = ({ x: scale, y: scale });
        },

      },
};
</script>

<style>
     body {
        margin: 0;
        padding: 0;
        overflow: hidden;
        background-color: #f0f0f0;
      }

      #stage-parent {
        width: 100%;
      }
</style>