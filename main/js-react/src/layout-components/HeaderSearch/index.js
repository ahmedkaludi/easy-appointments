import React, { Fragment, useRef, useState } from 'react';

import clsx from 'clsx';

import {
  Grid,
  Fab,
  Container,
  InputAdornment,
  Drawer,
  IconButton,
  List,
  ListItem,
  TextField,
  ListItemIcon,
  ListItemText,
  Divider
} from '@material-ui/core';

import SearchIcon from '@material-ui/icons/Search';

import CloseTwoToneIcon from '@material-ui/icons/CloseTwoTone';

const HeaderSearch = () => {
  const searchRef = useRef(null);

  const dummySearches1 = ['Analytics', 'Sales', 'Buttons', 'Cards'];

  const dummySearches2 = ['Helpdesk', 'Projects', 'Statistics'];

  const handleSearchChange = event => {
    setSearchValue(event.target.value);

    if (event.target.value) {
      if (!openSearchResults) {
        setOpenSearchResults(true);
      }
    } else {
      setOpenSearchResults(false);
    }
  };

  const [openSearchResults, setOpenSearchResults] = useState(false);
  const [searchValue, setSearchValue] = useState('');

  const [state, setState] = useState(false);

  const toggleDrawer = () => event => {
    if (
      event.type === 'keydown' &&
      (event.key === 'Tab' || event.key === 'Shift')
    ) {
      return;
    }

    setState(!state);
  };

  return (
    <Fragment>
      <IconButton
        size="medium"
        onClick={toggleDrawer(true)}
        color="inherit"
        className="btn-inverse font-size-xs">
        <SearchIcon />
      </IconButton>
      <Drawer anchor="top" open={state} onClose={toggleDrawer(false)}>
        <Container maxWidth="lg" className="py-4">
          <div className="d-flex justify-content-between">
            <div className="text-black">
              <h1 className="display-3 mb-2 font-weight-bold">Search</h1>
              <p className="font-size-lg text-black-50">
                Use the fields below to see search results
              </p>
            </div>
            <div className="d-flex align-items-center justify-content-center">
              <Fab onClick={toggleDrawer(false)} size="medium" color="primary">
                <CloseTwoToneIcon />
              </Fab>
            </div>
          </div>
        </Container>
        <div className="app-search-wrapper" ref={searchRef}>
          <Container maxWidth="lg">
            <TextField
              className="app-search-input"
              fullWidth
              value={searchValue}
              onChange={handleSearchChange}
              inputProps={{ 'aria-label': 'search' }}
              label="Search…"
              placeholder="Search terms here…"
              variant="outlined"
              InputProps={{
                startAdornment: (
                  <InputAdornment position="start">
                    <SearchIcon className="app-search-icon" />
                  </InputAdornment>
                )
              }}
            />
          </Container>
        </div>
        <Container maxWidth="lg" className="pb-3">
          <div
            className={clsx('no-search-results', {
              'search-results-hidden': openSearchResults
            })}>
            <div>
              <div className="text-warning font-weight-bold font-size-xl">
                No search results!
              </div>
              <p className="mb-0 font-size-lg text-black-50">
                Use the field above to begin searching for something
              </p>
            </div>
          </div>
          <div
            className={clsx('search-results', {
              'search-results-hidden': !openSearchResults
            })}>
            <div className="text-black py-4">
              <h3 className="font-size-xl mb-2 font-weight-bold">
                Search results
              </h3>
              <p className="text-black-50 font-size-lg">
                These are your results for{' '}
                <b className="text-black">{searchValue}</b>
              </p>
              <Divider />
            </div>
            <Grid container spacing={3}>
              <Grid sm={6} item>
                <List>
                  {dummySearches1.map(search => (
                    <ListItem
                      className="rounded-sm"
                      button
                      key={search}
                      onClick={toggleDrawer(false)}>
                      <ListItemIcon className="pr-3 min-w-auto">
                        <SearchIcon />
                      </ListItemIcon>
                      <ListItemText primary={search} />
                    </ListItem>
                  ))}
                </List>
              </Grid>
              <Grid sm={6} item>
                <List>
                  {dummySearches2.map(search => (
                    <ListItem
                      className="font-size-lg rounded-sm"
                      button
                      key={search}
                      onClick={toggleDrawer(false)}>
                      <ListItemIcon className="pr-3 min-w-auto">
                        <SearchIcon />
                      </ListItemIcon>
                      <ListItemText primary={search} />
                    </ListItem>
                  ))}
                </List>
              </Grid>
            </Grid>
          </div>
        </Container>
      </Drawer>
    </Fragment>
  );
};

export default HeaderSearch;
