<h1>Saya Ingin Mengedit Data {{ $edit->id }}</h1>
<h2>{{$edit->name}}</h2>

<form action="/obat/update/{{ $edit->id }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <input type="text" name="a" id="" value="{{ $edit->name }}" placeholder="Masukan nama obat..">
    <input type="text" name="b" id="" value="{{ $edit->description }}" placeholder="masukan deskripsi obat">
    <input type="number" name="c" id="" value="{{ $edit->price }}" placeholder="masukan harga obat..">
    <input type="date" name="d" id="" value="{{ $edit->expired }}" >
    
    <img src="/gambars/{{ $edit->medicine_image }}" width="100">
    <input type="file" name="image">
    <button type="submit">Submit</button>
</form>