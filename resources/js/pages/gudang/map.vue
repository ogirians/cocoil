<template>
  <div>
    <div class="tombol"> 
   
    <button v-if="editMode == true" @click="lookMode()"  style="margin: 10px 5px;margin-left: 15px;margin-right: 0px;" class="btn btn-primary btn-sm btn-sm btn-flat " type="button"><i class="glyphicon glyphicon-plus" ></i> look Mode </button>
    <button v-if="editMode == false" @click="editSlotMode()"  style="margin: 10px 5px;margin-left: 15px;margin-right: 0px;" class="btn btn-primary btn-sm btn-sm btn-flat " type="button"><i class="glyphicon glyphicon-plus" ></i> edit Mode </button>
    
    <button v-if="editMode == true" @click="addCoil"  style="margin: 10px 5px;margin-left: 15px;margin-right: 0px;" class="btn btn-primary btn-sm btn-sm btn-flat " type="button"><i class="glyphicon glyphicon-plus" ></i> Slot </button>
    <button v-if="editMode == true" @click="addNonCoil"  style="margin: 10px 5px;margin-left: 15px;margin-right: 0px;" class="btn btn-primary btn-sm btn-sm btn-flat " type="button"><i class="glyphicon glyphicon-plus" ></i> Object </button>
    <button v-if="setCoilButton == true && editMode == false" @click="SetCoil()"  style="margin: 10px 5px;margin-left: 15px;margin-right: 0px;" class="btn btn-primary btn-sm btn-sm btn-flat " type="button"><i class="glyphicon glyphicon-plus" ></i> Set COil </button>
		<button v-if="setRemoveButton == true && editMode == false" @click="removeCoil"  style="margin: 10px 5px;margin-left: 15px;margin-right: 0px;" class="btn btn-primary btn-sm btn-sm btn-flat " type="button"><i class="glyphicon glyphicon-plus" ></i> Remove </button>
    <button  style="margin: 10px 5px;margin-left: 5px;" class="btn btn-primary btn-sm btn-sm btn-flat " type="button"><i class="glyphicon glyphicon-plus" ></i> Text </button>	   
    </div>
    <v-stage 
          @mousedown="handleStageMouseDown"
          @touchstart="handleStageMouseDown"
          :config="stage"          
         >
    <v-layer >
       <v-group
      
           @dragstart="handleDragStart"
           @dragend="handleDragEnd"                                                            
           v-for="item in list"
          :key="item.id"
          :config="{name:item.name, id: item.id}" 
        >        

              <v-rect       
                 :config="item.rect"    
                 @transformend="handleTransformEnd"          
              >            
              </v-rect>   

              <v-image   
                            
                v-if="item.img"
                    :config="{
                        image: image, 
                        height: item.rect.height, 
                        width: item.rect.width, 
                        name: item.rect.name, 
                        x :item.rect.x, 
                        y :item.rect.y,
                        scaleX:item.rect.scaleX,
                        scaleY:item.rect.scaleY,
                        rotation:item.rect.rotation,                         
                        visible : editMode ? false : true,  
                      }"                              
                />        
                           
              <v-text                    
                   ref="text1"
                    :config="item.rectext"
                >
              </v-text>
             
                   
        </v-group>
             
      <v-transformer ref="transformer"
      v-if="editMode == false"
      :config="{ boundBoxFunc: function (oldBoundBox, newBoundBox) {
          
          if (Math.abs(newBoundBox.width) != selectedWidth) {
            return oldBoundBox;
          } else if (Math.abs(newBoundBox.height) != selectedHeigth){
            return oldBoundBox;
          }
          return newBoundBox;
          },
        }"
      />

      <v-transformer ref="transformer"
        v-else
      />
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
              setCoilButton: false,
              setRemoveButton :false,
              selectedShapeNameSerialcode: '',
              
              selectedImg: '',
              editMode: false,
              image : null,
              image2 : null,
             
              selectedWidth : '',
              selectedHeigth : '',
                  
            };


  },
  created(){    

            this.fitStageIntoParentContainer();
            // adapt the stage on any window resize
            window.addEventListener('resize', this.fitStageIntoParentContainer);

            const image = new window.Image();
            image.src = "/coil.png";

            const image2 = new window.Image();
            image2.src = "/coilSelected.png";

            image.onload = () => {
            // set image only when it is loaded
            this.image = image;
            this.image2 = image2;
            };
           
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
              img:false,
              serial_code: null,
              rectext : {id: id, x: 0, y:0, text:id, visible:true, name:'text'+ id, rotation: 0, fontSize :15} ,
              rect: {id:id, x: 0, y:0, fill : '#f7a7a7', width: 100, height: 150,  name:'group'+ id , scaleX: 1, scaleY: 1, rotation: 0, draggable:true, stroke :5, visible: true, imgCoil:''}              
            };
                   
           
            this.list.push(pos);          
           
            this.save();
          },

          SetCoil() { 
            
            const item = this.list.find(i => i.rect.name === this.selectedShapeName);
            const id = item.id;
            item.img =  true;
            item.rect.visible = false;
            item.serial_code = 'serial ' + id;
            
         // const  img = {src: '/coil.png', x:item.rect.x, y:item.rect.y} ;         
            
           // this.list.push(img);
            this.save();
          },

          removeCoil(){

            const item = this.list.find(i => i.rect.name === this.selectedShapeName);
            const id = item.id;
            item.img =  false;
            item.rect.visible = true;
            item.serial_code = null;
            //item.rect.draggable = false;
         // const  img = {src: '/coil.png', x:item.rect.x, y:item.rect.y} ;         
            
           // this.list.push(img);
            this.save();
      


          },         

          editSlotMode(){

            this.editMode = true;             

            const coil = this.list;

            coil.forEach(myFunction);

                function myFunction(item, index) {
             
                item.rect.visible = true;
                item.rect.draggable = true;
          
            }

          },  

          lookMode(){
            
            this.editMode = false;
            this.selectedShapeName = '';
            
            const coil = this.list;

            coil.forEach(myFunction);

            function myFunction(item, index) {
              if(item.serial_code != null){
                item.rect.visible = false;
              } else if (item.serial_code == null) {
                item.rect.visible = true;
                item.rect.draggable = false;
              }
               item.rectext.visible = true;
            }
            // const  img = {src: '/coil.png', x:item.rect.x, y:item.rect.y} ;         
            
           // this.list.push(img);
            this.updateTransformer();

            this.save();
            return;

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
            
            const item = this.list.find((i) => i.rect.id === e.target.id());
           
            //item.fill = 'red';
                        
            item.rect.x = e.target.x();
            item.rect.y = e.target.y(); 
            item.rectext.x = item.rect.x;
            item.rectext.y = item.rect.y; 
            
           // item.x = item.rect.x;
            //item.y = item.rect.y;     
            
            this.save();
            this.isDragging = false;
            this.dragItemId = null;
          },

          handleTransformEnd(e) {
           
            // shape is transformed, let us save new attrs back to the node
            // find element in our state
            
            const item = this.list.find(
              (r) => r.rect.name === this.selectedShapeName
            );
            // update the state
            item.rect.x = e.target.x();
            item.rect.y = e.target.y();
            item.rectext.x = item.rect.x;
            item.rectext.y = item.rect.y;
            
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
              this.selectedImg = '';

               const item = this.list.find((r) => r.rect.name ===  this.selectedShapeName); 
               this.selectedShapeName =  ''; 
               this.selectedShapeNameSerialcode = '';
               this.setCoilButton= false;
               this.setRemoveButton= false;
               this.brightness = 0;
               
               if (item) {
                item.rectext.visible = true;
                this.save();
               };
               
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
            
            //klik on coil
            if ( name.includes("img")){
              
                name.replace("img", "");


                const item = this.list.find((r) => r.rect.name === this.selectedShapeName);

                this.selectedImg = name;
                console.log(name);
                this.brightness = 0.8;
                
                this.save();
                this.selectCoilImg();
            }

           

            // cek jika pindah ke objec lain tanpa klik stage
            if (this.selectedShapeName != ''){
              if (this.selectedShapeName != name) {
                    const itemtext = this.list.find((r) => r.rect.name === this.selectedShapeName);
                    itemtext.rectext.visible = true;
                    this.save();
              }    
            }
            
            
            const item = this.list.find((r) => r.rect.name === name);            
            
            if (item) {                 

                this.selectedShapeName = name;
                
                this.selectedShapeNameSerialcode = item.serial_code
                
                //cek apakah sudah ada serial code
                if(!this.selectedShapeNameSerialcode){
                    this.setCoilButton = true;
                    this.setRemoveButton = false;
                    this.selectedWidth = item.rect.width;
                    this.selectedHeigth = item.rect.height;
                } else {
                    this.setCoilButton= false;
                    this.setRemoveButton = true;
                }
                item.rectext.visible = false;
              
            } else {
              this.selectedShapeName = '';
              
            }
            this.updateTransformer();
          },

          selectCoilImg(){
           

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
           
            
          },
          
        

      },
      mounted() {
          
          this.load();

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

      .tombol {
        background-color: white;
      }
</style>