<form>

                                    <div class="form-row">
                                        <div class="col-md-6 mb-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">País de residencia</label>
                                                <select class="form-control">
                                                  <option>Colombia</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Departamento</label>
                                                <select class="form-control">
                                                  <option>Chocó</option>
                                                </select>
                                            </div>                                        
                                        </div>
                                    </div>


                                    <div class="form-row">
                                        <div class="col-md-6 mb-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Municipio</label>
                                                <select class="form-control">
                                                  <option>Quibdó</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-6">
                                            <div class="form-group">
                                                <label for="tipo_zona">Tipo de zona</label>
                                                <select id="tipo_zona" name="tipo_zona" class="form-control">
                                                  <?php llenar_combo("SELECT id, nombre FROM tipo_zona ORDER BY nombre",true); ?>
                                                </select>
                                            </div>                                        
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-6 mb-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Dirección residencia</label>
                                                <input type="text" class="form-control" id="exampleInputEmail1"
                                                       aria-describedby="emailHelp">
                                            </div>

                                        </div>

                                        <div class="col-md-6 mb-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Telefono residencia</label>
                                                <input type="text" class="form-control" id="exampleInputEmail1"
                                                       aria-describedby="emailHelp">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-6 mb-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Telefono celular</label>
                                                <input type="text" class="form-control" id="exampleInputEmail1"
                                                       aria-describedby="emailHelp">
                                            </div>

                                        </div>

                                        <div class="col-md-6 mb-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Telefono oficina</label>
                                                <input type="text" class="form-control" id="exampleInputEmail1"
                                                       aria-describedby="emailHelp">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-6 mb-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Correo principal</label>
                                                <input type="text" class="form-control" id="exampleInputEmail1"
                                                       aria-describedby="emailHelp">
                                            </div>

                                        </div>

                                        <div class="col-md-6 mb-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Correo institucional</label>
                                                <input type="text" class="form-control" id="exampleInputEmail1"
                                                       aria-describedby="emailHelp">
                                            </div>
                                        </div>
                                    </div>
    
                                    <button type="submit" class="btn btn-primary">Agregar</button>
                                </form>