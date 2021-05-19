<table>
    <thead>
    <tr>
        <th>Solutations</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Address</th>
        <th>Customer Email</th>
        <th>Work Phone</th>
        <th>Mobile Phone</th>
        <th>Job Title</th>
    </tr>
    </thead>
    <tbody>
    @foreach($customer as $val)
        <tr>
            <td>{{ $val->customer_firstname }}</td>
            <td>{{ $val->customer_lastname }}</td>
            <td>{{ $val->customer_address }}</td>
            <td>{{ $val->customer_email }}</td>
            <td>{{ $val->customer_phone1 }}</td>
            <td>{{ $val->customer_phone2 }}</td>
            <td>{{ $val->customer_job }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
