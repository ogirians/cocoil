<template>
  <div>
    <div class="tombol"> 
    <button @click="addCoil"  style="margin: 10px 5px;margin-left: 15px;margin-right: 0px;" class="btn btn-primary btn-sm btn-sm btn-flat " type="button"><i class="glyphicon glyphicon-plus" ></i> Slot </button>
    <button @click="addNonCoil"  style="margin: 10px 5px;margin-left: 15px;margin-right: 0px;" class="btn btn-primary btn-sm btn-sm btn-flat " type="button"><i class="glyphicon glyphicon-plus" ></i> Object </button>
		<button  style="margin: 10px 5px;margin-left: 5px;" class="btn btn-primary btn-sm btn-sm btn-flat " type="button"><i class="glyphicon glyphicon-plus" ></i> Text </button>	   
    </div>
    <v-stage 
         
          :config="stage"          
         >
    <v-layer>
       <v-group              
          @dragstart="handleDragStart"
          @dragend="handleDragEnd"                       
           v-for="item in list"
          :key="item.id"
          :config="{ name:item.name, id: item.id, scaleX:item.scaleX, scaleY:item.scaleY, rotation:item.rotation, draggable:true}" 
          @transformend="handleTransformEnd">          
              <v-rect                
                 :config="item.rect"              
              >            
              </v-rect>          
              <v-text  
                   ref="text1"
                    :config="item.rectext"
                >
              </v-text>           
       </v-group>

      <v-transformer ref="transformer"/>
            
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

      dragItemId: null,
      
      tulisan : [{satu:'sjalan'}],

      stage: {
        container: 'container',
        width: this.sceneWidth,
        height: this.sceneHeight,
      },
      list: [],
      listNonCoil:[{}],
      
      listText: [
        {x:100, y:100, text: 'Draggable 1', name:'rect'},
        {x:100, y:50, text: 'Draggable 2', name:'rect2'},
      ],
          
      isDragging: false,  
      selectedShapeName: '',

      group:'',
           
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
          const id = Math.round(Math.random() * 10000).toString();          
            const pos = {
              rotation: 0,
              scaleX: 1, scaleY: 1,             
              id: id,              
              name: 'group'+ id,
              draggable:true,
              rectext : { x: 100, y:100, text:id, name:'rectecx'+id} ,
              rect: { x: 120, y:110, fill : 'red', width: 100, height: 100,  name:'rect'+id}             
            };

          
           
            this.list.push(pos);          
           
            this.save();
          },

          addNonCoil() {  
          const id = Math.round(Math.random() * 10000).toString();          
            const pos = {
              rotation: 0,
              x: 100, 
              y: 100, 
              id: id,
              fill : 'grey',
              width: 100,
              height: 100,
              scaleX: 1,
              scaleY: 1,
              name: 'rectNon'+ id,
            };

            this.list.push(pos);
            this.save();
          },

          handleDragStart(e) {
            this.isDragging = true;

            // save drag element:
            this.dragItemId = e.target.id();
           
           
            // move current element to the top:
            const item = this.list.find(i => i.id === this.dragItemId);
            const index = this.list.indexOf(item);
            //item.fill = 'blue';
            this.list.splice(index, 1);
            this.list.push(item);

          },
          handleDragEnd(e) {
            this.dragItemId = e.target.id();
            
            const item = this.list.find((i) => i.id === this.dragItemId);
           
            //item.fill = 'red';
            item.x = e.target.x();
            item.y = e.target.y();     
            
            this.save();
            this.isDragging = false;
            this.dragItemId = null;
          },

          handleTransformEnd(e) {
            //this.selectedShapeName = e.target.name();
            // shape is transformed, let us save new attrs back to the node
            // find element in our state
            
            const item = this.list.find(
              (r) => r.name === this.selectedShapeName
            );
            // update the state
            item.x = e.target.x();
            item.y = e.target.y();
            item.rotation = e.target.rotation();
            item.scaleX = e.target.scaleX();
            item.scaleY = e.target.scaleY();
           
            this.save();
            // change fill
            //rect.fill = Konva.Util.getRandomColor();
          },

          handleStageMouseDown(e) {
            // clicked on stage - clear selection
            if (e.target === e.target.getStage()) {
              this.selectedShapeName =  '';
              this.updateTransformer();
              return;
            }

            // clicked on transformer - do nothing
            const clickedOnTransformer =
              e.target.getParent().className === 'Transformer';
            if (clickedOnTransformer) {
              return;
            }

            // find clicked rect by its name
            const name = e.target.name();
            const item = this.list.find((r) => r.name === name);
            if (item) {
              this.selectedShapeName = name;
            } else {
              this.selectedShapeName = '';
            }
            this.updateTransformer();
          },

          updateTransformer() {
            // here we need to manually attach or detach Transformer node
            const transformerNode = this.$refs.transformer.getNode();
            const stage = transformerNode.getStage();
            const { selectedShapeName } = this;

            const selectedNode = stage.findOne('.' + selectedShapeName);
            // do nothing if selected node is already attached
            if (selectedNode === transformerNode.node()) {
              return;
            }

            if (selectedNode) {
              // attach to another node
              transformerNode.nodes([selectedNode]);
            } else {
              // remove transformer
              transformerNode.nodes([]);
            }
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