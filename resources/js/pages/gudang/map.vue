<template>
  <div>
    <div class="tombol"> 
    <button @click="addCoil"  style="margin: 10px 5px;margin-left: 15px;margin-right: 0px;" class="btn btn-primary btn-sm btn-sm btn-flat " type="button"><i class="glyphicon glyphicon-plus" ></i> Slot </button>
    <button @click="addNonCoil"  style="margin: 10px 5px;margin-left: 15px;margin-right: 0px;" class="btn btn-primary btn-sm btn-sm btn-flat " type="button"><i class="glyphicon glyphicon-plus" ></i> Object </button>
		<button  style="margin: 10px 5px;margin-left: 5px;" class="btn btn-primary btn-sm btn-sm btn-flat " type="button"><i class="glyphicon glyphicon-plus" ></i> Text </button>	   
    </div>
    <v-stage 
          @mousedown="handleStageMouseDown"
          @touchstart="handleStageMouseDown"
          :config="stage"          
         >
    <v-layer
          >
       <v-group
                                                                     
           v-for="item in list"
          :key="item.id"
          :config="{name:item.name, id: item.id, draggable:true}" 
        >          
              <v-rect                      
                  @dragstart="handleDragStart"
                  @dragend="handleDragEnd" 
                  @transformend="handleTransformEnd"             
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
          
      stage: {
        container: 'container',
        width: this.sceneWidth,
        height: this.sceneHeight,
      },
      list: [],
      listNonCoil:[{}],   
             
      isDragging: false,  
      selectedShapeName: '',

           
    };


  },
  created(){    
      this.fitStageIntoParentContainer();
      // adapt the stage on any window resize
      window.addEventListener('resize', this.fitStageIntoParentContainer);
      
      var group = new Konva.Group();
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
              id: id,                   
              name: 'gr'+ id,
              draggable:true,
              width: 100,
              height: 130,
              rectext : {id: id, x: 0, y:0, text:id, visible:true, name:'text'+ id, rotation: 0, } ,
              rect: {id:id, x: 0, y:0, fill : 'red', width: 100, height: 150,  name:'group'+ id , scaleX: 1, scaleY: 1, rotation: 0, }              
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
            console.log(this.dragItemId)
           
            // move current element to the top:
            const item = this.list.find(i => i.id === this.dragItemId);
            const index = this.list.indexOf(item);
            //item.fill = 'blue';
            this.list.splice(index, 1);
            this.list.push(item);

          },
          handleDragEnd(e) {
            //this.dragItemId = e.target.name();
            
            const item = this.list.find((i) => i.id === e.target.id());
           
            //item.fill = 'red';
                        
            item.rect.x = e.target.x();
            item.rect.y = e.target.y();  
            
            item.x = item.rect.x;
            item.y = item.rect.y;     
            
            this.save();
            this.isDragging = false;
            this.dragItemId = null;
          },

          handleTransformEnd(e) {
            var aaa = e.target.name() ;
            console.log(aaa);
            // shape is transformed, let us save new attrs back to the node
            // find element in our state
            
            const item = this.list.find(
              (r) => r.rect.name === this.selectedShapeName
            );
            // update the state
            item.rect.x = e.target.x();
            item.rect.y = e.target.y();
            item.rectext.x = item.rect.x;
            item.rectext.y = item.rect.y-20;
            
            item.rect.rotation = e.target.rotation();
            item.rectext.rotation = item.rect.rotation;
            item.rect.scaleX = e.target.scaleX();
            item.rect.scaleY = e.target.scaleY();
           
            this.save();
            // change fill
            //rect.fill = Konva.Util.getRandomColor();
          },

          handleStageMouseDown(e) {
           
            // clicked on stage - clear selection
            if (e.target === e.target.getStage()) {

              const item = this.list.find((r) => r.rect.name === this.selectedShapeName); 
              this.selectedShapeName =  '';
             
              item.rectext.visible = true;
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
           
            console.log(name);
            const item = this.list.find((r) => r.rect.name === name);            
            
            if (item) {
              this.selectedShapeName = name;
              item.rectext.visible = false;
              
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