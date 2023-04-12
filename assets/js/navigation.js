const navbarLinks = document.querySelectorAll('.navbar a')
const path = window.location.pathname

const currentRoute = path.split('/')?.[2]

for (let i = 0; i < navbarLinks.length; i++) {
	const link = navbarLinks[i]
	if (link.getAttribute('href') === currentRoute) {
		link.classList.add('active')
		break
	}
}
