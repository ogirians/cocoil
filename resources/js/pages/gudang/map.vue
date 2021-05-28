<template>
    <v-stage :config="configKonva">
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
      configKonva: {
        width: 400,
        height: 400
      },
      configCircle: {
        x: 0,
        y: 0,
        radius: 70,
        fill: "red",
        stroke: "black",
        strokeWidth: 4,
        draggable:true
      }
    };
  }
};
</script>