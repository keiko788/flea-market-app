<x-auth-layout>
    <div class="mx-auto mt-[237px] mb-[370px] text-center">
        <p class="text-2xl font-bold text-center mb-[62px]">
            登録していただいたメールアドレスに認証メールを送付しました。
            <br>
            メール認証を完了してください。
        </p>
        <a href="http://localhost:8025" class="text-2xl font-bold bg-[#D9D9D9]
        w-[257px] h-[69px] border border-[#000] rounded-[10px]
        flex items-center justify-center  mx-auto mb-[52px]"
        target="_blank">
            認証はこちらから
        </a>
        <form action="{{ route('verification.send') }}" method="POST">
            @csrf
            <button type="submit" class="text-xl text-[#0073CC]">
                認証メールを再送する
            </button>
        </form>
    </div>
</x-auth-layout>