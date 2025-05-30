import Landingpage from "./pages/Landingpage/Landingpage";
import { BrowserRouter } from "react-router";
import { Route } from "react-router";
import { Routes } from "react-router";

function App() {
  /* routes folder */
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<Landingpage />} />
      </Routes>
    </BrowserRouter>
  );
}

export default App;
