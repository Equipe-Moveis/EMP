<div class="row">
    <div class="input-field col s12 m6">
        <i class="material-icons prefix">person</i>
        <input id="name" type="text" name="name" maxlength="100"
               value="{{ old('name', $usuario->name) }}" class="@error('name') invalid @enderror">
        <label for="name" class="{{ old('name', $usuario->name) ? 'active' : '' }}">Nome *</label>
        @error('name') <span class="helper-text red-text text-lighten-2">{{ $message }}</span> @enderror
    </div>

    <div class="input-field col s12 m6">
        <i class="material-icons prefix">email</i>
        <input id="email" type="email" name="email" maxlength="255"
               value="{{ old('email', $usuario->email) }}" class="@error('email') invalid @enderror">
        <label for="email" class="{{ old('email', $usuario->email) ? 'active' : '' }}">E-mail *</label>
        @error('email') <span class="helper-text red-text text-lighten-2">{{ $message }}</span> @enderror
    </div>
</div>

<div class="row">
    <div class="input-field col s12 m4">
        <select id="perfil" name="perfil">
            @php $perfilAtual = old('perfil', $usuario->perfil ?? 'funcionario'); @endphp
            <option value="funcionario" @selected($perfilAtual === 'funcionario')>Funcionário</option>
            <option value="admin" @selected($perfilAtual === 'admin')>Administrador</option>
        </select>
        <label for="perfil">Perfil *</label>
        @error('perfil') <span class="helper-text red-text text-lighten-2">{{ $message }}</span> @enderror
    </div>

    <div class="input-field col s12 m4">
        <i class="material-icons prefix">lock</i>
        <input id="password" type="password" name="password" class="@error('password') invalid @enderror"
               placeholder="{{ $usuario->exists ? 'Deixe em branco para manter a atual' : '' }}">
        <label for="password" class="{{ $usuario->exists ? 'active' : '' }}">
            {{ $usuario->exists ? 'Nova senha' : 'Senha *' }}
        </label>
        @error('password') <span class="helper-text red-text text-lighten-2">{{ $message }}</span> @enderror
    </div>

    <div class="input-field col s12 m4">
        <i class="material-icons prefix">lock_outline</i>
        <input id="password_confirmation" type="password" name="password_confirmation">
        <label for="password_confirmation">Confirmar senha</label>
    </div>
</div>

<div class="row">
    <div class="col s12">
        <button class="btn waves-effect waves-light" type="submit">
            <i class="material-icons left">save</i>Salvar
        </button>
        <a href="{{ route('usuarios.index') }}" class="btn waves-effect waves-light grey darken-1" style="margin-left: 8px;">
            <i class="material-icons left">cancel</i>Cancelar
        </a>
    </div>
</div>