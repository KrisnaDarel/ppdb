<table border="1">

    <tr>
        <td>Kode</td>
        <td>
            {{$siswa->kode}}
        </td>
    </tr>

    <tr>
        <td>Nama Siswa</td>
        <td>
            {{$siswa->nama}}
        </td>
    </tr>

    <tr>
        <td>Jurusan</td>
        <td>
            {{$siswa->jurusan->nama}}
        </td>
    </tr>

    <tr>
        <td>Alamat</td>
        <td>
            {{$siswa->alamat}}
        </td>
    </tr>
</table>