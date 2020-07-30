    @extends('principal')
    @section('contenido')

        @if(Auth::check())
            @if (Auth::user()->id == 1)
                <template v-if="menu==0" >
                    <h1>Contenido del menu 0</h1>
                </template>
                <template v-if="menu==1" >
                    <categoria></categoria>
                </template>
                <template v-if="menu==2" >
                    <premio></premio>
                </template>
                <template v-if="menu==3" >
                    <disciplina></disciplina>
                </template>
                <template v-if="menu==4" >
                    <sancion></sancion>
                </template>
                <template v-if="menu==5" >
                    <oficial></oficial>
                </template>
                <template v-if="menu==6" >
                    <cadete></cadete>
                </template>
                <template v-if="menu==7" >
                    <merito></merito>
                </template>
                <template v-if="menu==8" >
                    <demerito></demerito>
                </template>
                <template v-if="menu==9" >
                    <h1>Contenido del menu 9</h1>
                </template>
                <template v-if="menu==10" >
                    <rol></rol>
                </template>
                <template v-if="menu==11" >
                    <lista-franco-de-honor></lista-franco-de-honor>
                </template>
                <template v-if="menu==12" >
                    <h1>Contenido del menu 12</h1>
                </template>
                <template v-if="menu==13" >
                    <h1>Contenido del menu 13</h1>
                </template>
                <template v-if="menu==14" >
                    <h1>Contenido del menu 14</h1>
                </template>
            @elseif (Auth::user()->idrol == 2)
                <template v-if="menu==1" >
                    <categoria></categoria>
                </template>
                <template v-if="menu==2" >
                    <premio></premio>
                </template>
                <template v-if="menu==3" >
                    <disciplina></disciplina>
                </template>
                <template v-if="menu==4" >
                    <sancion></sancion>
                </template>
                <template v-if="menu==5" >
                    <oficial></oficial>
                </template>
                <template v-if="menu==6" >
                    <cadete></cadete>
                </template>
                <template v-if="menu==7" >
                    <merito></merito>
                </template>
                <template v-if="menu==8" >
                    <demerito></demerito>
                </template>
                <template v-if="menu==9" >
                    <h1>Contenido del menu 9</h1>
                </template>
                <template v-if="menu==10" >
                    <rol></rol>
                </template>
                <template v-if="menu==11" >
                    <lista-franco-de-honor></lista-franco-de-honor>
                </template>
                <template v-if="menu==12" >
                    <h1>Contenido del menu 12</h1>
                </template>
                <template v-if="menu==13" >
                    <h1>Contenido del menu 13</h1>
                </template>
                <template v-if="menu==14" >
                    <h1>Contenido del menu 14</h1>
                </template>

            @elseif (Auth::user()->idrol == 3)
                <template v-if="menu==7" >
                    <merito></merito>
                </template>
                <template v-if="menu==8" >
                    <demerito></demerito>
                </template>
                <template v-if="menu==9" >
                    <h1>Contenido del menu 9</h1>
                </template>
                <template v-if="menu==10" >
                    <rol></rol>
                </template>
                <template v-if="menu==11" >
                    <lista-franco-de-honor></lista-franco-de-honor>
                </template>
                <template v-if="menu==12" >
                    <h1>Contenido del menu 12</h1>
                </template>
                <template v-if="menu==13" >
                    <h1>Contenido del menu 13</h1>
                </template>
                <template v-if="menu==14" >
                    <h1>Contenido del menu 14</h1>
                </template>

            @else

            @endif

        @endif


    @endsection