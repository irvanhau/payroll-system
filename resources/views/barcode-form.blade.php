<!DOCTYPE html>
<html>
<head>
    <title>Generate QR Code from Excel</title>
</head>
<body>
    <h1>Generate QR Code from Excel</h1>
    
    <form action="{{ route('qrcode.process') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div>
            <label for="file">Excel File:</label>
            <input type="file" name="file" id="file" required>
        </div>
        
        <div>
            <label for="column">Column to Generate QR Code:</label>
            <input type="text" name="column" id="column" required>
        </div>
        
        <button type="submit">Generate QR Codes</button>
    </form>
</body>
</html>