<header class="page-header">
    <div>
        <p class="eyebrow">Hybrid chatbot</p>
        <h1>Ask an academic question</h1>
        <p class="page-subtitle">The chatbot checks general FAQs and materials from your enrolled courses.</p>
    </div>
</header>

<section class="chat-layout">
    <div class="panel chat-panel">
        <div id="chatLog" class="chat-log">
            <div class="bot-bubble bubble-animate">Hello. Ask about courses, announcements, timetable, registration, or lecturer communication.</div>
            <?php if ($answer = flash('chat_answer')): ?>
                <div class="bot-bubble bubble-animate"><?= e($answer) ?></div>
            <?php endif; ?>
        </div>
        <form id="chatForm" class="inline-form" method="post" action="<?= e(url('student/chatbot')) ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="ajax" value="1">
            <label>
                <span>Your question</span>
                <input name="question" placeholder="When is course registration closing?" required autocomplete="off">
            </label>
            <button class="btn primary" type="submit">Ask chatbot</button>
        </form>
    </div>
    <aside class="panel">
        <h2>FAQ knowledge base</h2>
        <?php foreach (array_slice($faqs, 0, 8) as $faq): ?>
            <article class="list-item">
                <strong><?= e($faq['question']) ?></strong>
                <span><?= e($faq['category']) ?></span>
            </article>
        <?php endforeach; ?>
    </aside>
</section>
