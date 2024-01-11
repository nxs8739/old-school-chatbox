const cells = document.querySelectorAll('.cell');
let currentPlayer = 'X';
let gameActive = true;
let playerScore = 0;
let aiScore = 0;

function checkWinner() {
    const winningCombos = [
        [0, 1, 2], [3, 4, 5], [6, 7, 8],
        [0, 3, 6], [1, 4, 7], [2, 5, 8],
        [0, 4, 8], [2, 4, 6]
    ];

    for (const combo of winningCombos) {
        const [a, b, c] = combo;
        if (cells[a].innerText && cells[a].innerText === cells[b].innerText && cells[a].innerText === cells[c].innerText) {
            cells[a].classList.add('winner');
            cells[b].classList.add('winner');
            cells[c].classList.add('winner');
            gameActive = false;
            return true;
        }
    }
    return false;
}

function checkTie() {
    return Array.from(cells).every(cell => cell.innerText);
}

function handleClick(cellIndex) {
    if (!gameActive || cells[cellIndex].innerText) {
        return;
    }

    cells[cellIndex].innerText = currentPlayer;
    cells[cellIndex].classList.add(currentPlayer);

    if (checkWinner()) {
        if (currentPlayer === 'X') {
            playerScore++;
        } else {
            aiScore++;
        }
        updateScore();
        alert(currentPlayer + ' wins!');
        setTimeout(resetGame, 1000);
        return;
    }

    if (checkTie()) {
        alert("It's a tie!");
        setTimeout(resetGame, 1000);
        return;
    }

    if (currentPlayer === 'X') {
        currentPlayer = 'O';
        makeAiMove();
    } else {
        currentPlayer = 'X';
    }
}

function updateScore() {
    document.querySelector('.player-score').textContent = playerScore;
    document.querySelector('.ai-score').textContent = aiScore;
}

function makeAiMove() {
    const emptyCells = Array.from(cells).filter(cell => !cell.innerText);
    if (emptyCells.length > 0) {
        const randomIndex = Math.floor(Math.random() * emptyCells.length);
        const randomCell = emptyCells[randomIndex];
        setTimeout(() => handleClick(Array.from(cells).indexOf(randomCell)), 500);
    }
}

function resetGame() {
    currentPlayer = 'X';
    gameActive = true;

    cells.forEach(cell => {
        cell.innerText = '';
        cell.classList.remove('winner', 'X', 'O');
    });

    updateScore();
    if (currentPlayer === 'O') {
        makeAiMove();
    }
}

updateScore();
cells.forEach(cell => cell.addEventListener('click', () => handleClick(Array.from(cells).indexOf(cell))));