@extends('auth.contenido')

@section('login')
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card-group mb-0">
            <div class="card p-4">
                <form class="form-horizontal was-validated" method="POST" action="{{ route('login')}}">
                {{ csrf_field() }}
                    <div class="card-body">
                    <style>
                      img {
                        display: block;
                        margin-left: auto;
                        margin-right: auto;
                      }
                    </style>
                    <img class="center" src="img/logo.png" width="300" height="100">
                    
                    <p class="text-muted">Control de acceso al sistema</p>
                    <div class="form-group mb-3{{$errors->has('email' ? 'is-invalid' : '')}}">
                        <span class="input-group-addon"><i class="icon-user"></i></span>
                        <input type="text" value="{{old('email')}}" name="email" id="email" class="form-control" placeholder="Email">
                        {!!$errors->first('email','<span class="invalid-feedback">:message</span>')!!}
                    </div>
                    <div class="form-group mb-4{{$errors->has('password' ? 'is-invalid' : '')}}">
                        <span class="input-group-addon"><i class="icon-lock"></i></span>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                        {!!$errors->first('password','<span class="invalid-feedback">:message</span>')!!}
                    </div>
                    <div class="row">
                        <div class="col-6">
                        <button type="submit" class="btn btn-primary px-4">Acceder</button>
                        </div>
                        <div class="col-6 text-right">
                        <button type="button" class="btn btn-link px-0">Olvidaste tu password?</button>
                        </div>
                    </div>
                    </div>
               </form>
            </div>
          <div class="card text-white bg-primary py-5 d-md-down-none" style="width:44%">
            <div class="card-body text-center">
              <div>
                <!--<img src="paris.jpg" class="mx-auto d-block" style="width:20%"> -->
                <h2>Sistema de Control de Conducta y Disciplina</h2>
                <p>Sistema desarrollado en PHP utilizando el Framework Laravel y Vue Js, con el gestor de base de datos MariaDB.</p>
                <a href="https://www.fab.bo/" target="_blank" class="btn btn-primary active mt-3">Ver www.fab.bo</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection