<template>
  <div>
    <div class="tombol"> 
    <button @click="addCoil"  style="margin: 10px 5px;margin-left: 15px;margin-right: 0px;" class="btn btn-primary btn-sm btn-sm btn-flat " type="button"><i class="glyphicon glyphicon-plus" ></i> coil </button>
		<button  style="margin: 10px 5px;margin-left: 5px;" class="btn btn-primary btn-sm btn-sm btn-flat " type="button"><i class="glyphicon glyphicon-plus" ></i> arah </button>	   
    </div>
    <v-stage :config="stage" 
          @dragstart="handleDragStart"
          @dragend="handleDragEnd">
    <v-layer>
         <v-circle          
          v-for="item in list"
          :key="item.id"
          :config="{
            x : item.x, y: item.y, radius: 70, fill: isDragging ? 'blue' : 'red', draggable:true, stroke:'black'
          }"></v-circle>     
    </v-layer>
  </v-stage>
  </div>
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
      list: [{ 
        x: 100, 
        y: 100,
        id : 1, 
        radius: 50, 
        fill: 'blue' 
        }],  
      isDragging: false,       
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
        
          addCoil() {            
            const pos = {
              x: 100, 
              y: 100, 
              id: Math.round(Math.random() * 10000).toString(),
            };

            this.list.push(pos);
            this.save();
          },

          handleDragStart() {
            this.isDragging = true;
          },
          handleDragEnd() {
            this.isDragging = false;
          },

          load() {
            const data = localStorage.getItem('storage') || '[]';
            this.list = JSON.parse(data);
          },

          save() {
            localStorage.setItem('storage', JSON.stringify(this.list));
          }

      },
      mounted() {
          this.load();
      }
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

      .tombol {
        background-color: white;
      }
</style>