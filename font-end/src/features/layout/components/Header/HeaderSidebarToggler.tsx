'use client'

// Tạm thời comment out vì chưa có SidebarProvider
// import { useSidebar } from '@/components/Layout/Dashboard/SidebarProvider'
import { Button } from 'react-bootstrap'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faBars } from '@fortawesome/free-solid-svg-icons'

export default function HeaderSidebarToggler() {
  // Tạm thời comment out vì chưa có useSidebar
  // const {
  //   showSidebarState: [isShowSidebar, setIsShowSidebar],
  // } = useSidebar()

  const toggleSidebar = () => {
    // setIsShowSidebar(!isShowSidebar)
    console.log('Toggle sidebar clicked')
  }

  return (
    <Button
      variant="link"
      className="header-toggler rounded-0 shadow-none"
      type="button"
      onClick={toggleSidebar}
    >
      <FontAwesomeIcon icon={faBars} />
    </Button>
  )
}
