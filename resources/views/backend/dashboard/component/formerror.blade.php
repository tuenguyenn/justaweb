@if ($errors->any())
            <div class="error-message-custom">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
 @endif
 