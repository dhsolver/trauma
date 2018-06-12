@if (!empty($s3Data))
<script type="text/javascript">
    var S3_MAX_SIZE = {{ $s3Data['max_size'] }};
</script>

<form id="s3-form" action="//{{ $s3Data['bucket'] }}.s3-{{ $s3Data['region'] }}.amazonaws.com/" method="post" enctype="multipart/form-data">
    <input type="hidden" name="key" value="" />
    <input type="hidden" name="acl" value="public-read" />
    <input type="hidden" name="X-Amz-Credential" value="{{ $s3Data['access_key'] }}/{{ $s3Data['short_date'] }}/{{ $s3Data['region'] }}/s3/aws4_request" />
    <input type="hidden" name="X-Amz-Algorithm" value="{{ $s3Data['hash_algorithm'] }}" />
    <input type="hidden" name="X-Amz-Date" value="{{ $s3Data['iso_date'] }}" />
    <input type="hidden" name="Policy" value="{{ $s3Data['policy'] }}" />
    <input type="hidden" name="X-Amz-Signature" value="{{ $s3Data['signature'] }}" />
    <input type="hidden" name="Content-Type" value="" />
</form>
@endif
