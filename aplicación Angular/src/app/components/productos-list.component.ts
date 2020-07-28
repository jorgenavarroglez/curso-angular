import { Component } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { ProductoService } from '../services/producto.service';
import { Producto } from '../models/producto';

@Component({
	selector: 'productos-list',
	templateUrl: '../views/productos-list.html',
	providers: [ProductoService]
})

export class ProductosListComponent{

	public titulo: string;
	public productos: Producto[];
	public confirmado = null;

	constructor(
		private _route: ActivatedRoute,
		private _router: Router,
		private _productoService: ProductoService
	){
		this.titulo = 'Listado de productos';
	}

	ngOnInit(){
		console.log('Se ha cargado el productos-list.component.ts');

		this.getProductos();
	}

	getProductos(){
		this._productoService.getProductos().subscribe(
			result => {
				if(result.code != 200){
					console.log(result);
				}else{
					this.productos = result.data;
				}
			},
			error => {
				console.log(<any>error);
			}
		);
	}

	borrarConfirmado(id){
		this.confirmado = id;
	}

	cancelarConfirmado(){
		this.confirmado = null;
	}

	onDeleteProducto(){
		if(this.confirmado!=null){
			this._productoService.deleteProducto(this.confirmado).subscribe(
				result => {
					if(result.code == 200){
						this.getProductos();
					}else{
						alert('Error al borrar el producto.');
					}
				},error => {
					console.log(<any>error);
				}
			);
		}
		this.confirmado = null;
	}

}