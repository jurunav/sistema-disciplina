    @extends('principal')
    @section('contenido')

        @if(Auth::check())
            <template v-if="menu==0" >
                <inicio></inicio>
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
                <user></user>
            </template>
            <template v-if="menu==10" >
                <rol></rol>
            </template>
            <template v-if="menu==11" >
                <lista-franco-de-honor></lista-franco-de-honor>
            </template>
            <template v-if="menu==12" >
                <control-merito-demerito></control-merito-demerito>
            </template>
            <template v-if="menu==13" >
                <h1>Contenido del menu 13</h1>
            </template>
            <template v-if="menu==14" >
                <h1>Contenido del menu 14</h1>
            </template>
        @endif


    @endsection