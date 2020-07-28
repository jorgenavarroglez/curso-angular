import { Component } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';

import { ProductoService } from '../services/producto.service';
import { Producto } from '../models/producto';
import { GLOBAL } from '../services/global';

@Component({
	selector: 'producto-add',
	templateUrl: '../views/producto-add.html',
	providers: [ProductoService]
})

export class ProductoAddComponent{
	public titulo: string;
	public producto: Producto;
	public filesToUpload;
	public resultUpload;
	public is_edit;

	constructor(
		private _productoService: ProductoService,
		private _route: ActivatedRoute,
		private _router: Router
	){
		this.titulo = 'Crear un nuevo producto';
		this.producto = new Producto(0, '', '', 0, '');
		this.is_edit = false;
	}

	ngOnInit(){
		console.log('El componente producto-add.component.ts ha sido cargado.');
	}

	onSubmit(){
		console.log(this.producto);
		if(this.filesToUpload && this.filesToUpload.length >= 1){
			this._productoService.makeFileRequest(GLOBAL.url+'upload-file', [], this.filesToUpload).then((result) => {
				console.log(result);
				this.resultUpload = result;
				this.producto.imagen = this.resultUpload.filename;
				this.saveProducto();

			},(error) =>{
				console.log(error);
			});
		}else{
			this.saveProducto();
		}
	}

	saveProducto(){
		this._productoService.addProducto(this.producto).subscribe(
			result => {
				if(result.code == 200){
					this._router.navigate(['/productos']);
				}else{
					console.log(result);
				}
			},
			error => {
				console.log(<any>error);
			}
		);
	}

	fileChangeEvent(fileInput: any){
		this.filesToUpload = <Array<File>>fileInput.target.files;
		console.log(this.filesToUpload);
	}
}